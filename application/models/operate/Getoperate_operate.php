<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/20
 * Time: 9:59
 */

class Getoperate_operate extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
        set_time_limit(0);
        $this->load->model ( 'data/operate_data' );
        $this->load->model ( 'data/shop_data' );
        $this->load->model ( 'data/admin_data' );
        $this->load->model ( 'data/royalty_rules_data' );
        $this->load->model ( 'data/order_goods_data' );
    }

    //根据店铺获取前天的运营数据存入数据库
    public function get_datas()
    {
        //获取当前所有店铺
        $shops = $this->shop_data->get_field_by_where(['id','user_id'],['status'=>1],true);

        //没有店铺时 跳出程序
        if(empty($shops)){  return false;  }

        $datatime = date("Y-m-d",strtotime("-2 day"));
        $time = date('Y-m-d H:i:s'); //当前时间

        foreach ($shops as $v){//循环店铺

            $data = [];//初始化数组
            $data['datetime'] = $datatime;
            $data['insert_time'] = $time; //添加数据时间
            $data['shop_id'] = $v['id'];//店铺id
            $data['user_id'] = $v['user_id'];//用户ID

            $data = $this->common_operate($data);

            if($data){
                //将数据存入数据库
                $this->operate_data->store($data);
            }
        }
    }

    /**
     * 更新指定店铺指定时间的运营数据
     * @param $shop_id 店铺ID
     * @param $date  时间 例：2020-05-16
     * @return bool
     */
    public function get_data( $shop_id , $date )
    {
        set_time_limit(0);
        $today = date("Y-m-d");//今天的日期
        $time = date('Y-m-d H:i:s');//当前时间

        if($shop_id && $date && $date < $today){
            //查询是否存在运营记录
            $data = $this->operate_data->find(['shop_id'=>$shop_id,'datetime'=>$date]);

            if(empty($data)){ //没有运营数据的时候，查询店铺信息

                $data['shop_id'] = $shop_id;
                $data['datetime'] = $date;
                $data['insert_time'] = $time; //添加数据时间

                $shops = $this->shop_data->get_field_by_where(['user_id'],['id'=>$shop_id]);

                if(empty($shops)){ //没有店铺信息，弹出程序
                    return '未获取到该店铺信息';
                }
                $data['user_id'] = $shops['user_id'];
            }

            $data['update_time'] = $time; //添加数据时间

            $data = $this->common_operate_used($data); //更新

            if($data['id']){//修改
                $id = $data['id'];
                unset($data['id']);
                $this->operate_data->update($id,$data);
            }else{//新增
                //将数据存入数据库
                $this->operate_data->store($data);
            }
        }
    }


    /**
     * 计算某个店铺某个时间的运营数据
     * @param array $data
     * @return array
     */
    public function common_operate($data = []){

        //获取店铺指定时间的总营业额,付款订单数,付款订单id,运费 (状态为已支付)
        $sql = "SELECT SUM(total_price_usd) AS turnover,count(shopify_o_id) AS paid_orders,
                            SUM(freight) AS freight_sum FROM `order` WHERE
                           shop_id = {$data['shop_id']} AND datetime = '{$data['datetime']}' AND financial_status = 'paid'";
        $orderSum =$this->db->query ( $sql )->row_array ();
        $data['turnover'] = max(0,$orderSum['turnover']);//店铺总营业额
        $data['paid_orders'] = max(0,$orderSum['paid_orders']);//付款订单数
        $data['freight_sum'] = max(0,$orderSum['freight_sum']);//运费

        //根据user_id获取部门id
        $org_id = $this->db->query ( "select org_id from admin where id = {$data['user_id']}" )->row_array ()['org_id'];
        if(empty($org_id)){ //当员工没有部门的时候
            $data['operate_remark'] = '该用户没有相应部门';
            return $data;
        }

        //根据部门id获取相应的提成规则的手续费(百分比),汇率，每克运费，挂号费
        $fees = $this->royalty_rules_data->find(['o_id'=>$org_id]);
        if(empty($fees)){
            $data['operate_remark'] = '缺少提成规则';
            return $data;
        }

        $data['exchange_rate'] = max(0,$fees['exchange_rate']);//汇率
        $data['freight'] = max(0,$fees['freight']);//每克运费
        $data['service_charge'] = max(0,$fees['service_charge']);//手续费 %
        $data['register_cost'] = max(0,$fees['register_fee']);//挂号费

        //获取对应的sku_总成本,商品总重量,sku是否存在标识
        $cost = $this->get_cost($data);
        if($cost===2){
            $data['operate_remark'] = '请填写sku信息';
            return $data;
        }
        if($cost===1){
            $data['operate_remark'] = '该店铺有sku没有匹配成功';
            return $data;
        }

        $data['sku_total_cost'] = $cost['sku_total_cost'];//sku总成本(人民币)
        $data['total_weight'] = $cost['total_weight']; //sku商品总重量(g)

        //客单价 = 营业额/付款订单数
        $data['unit_price'] = $data['turnover']?bcdiv($data['turnover'],$data['paid_orders'],2):0;
        $data['unit_price'] = empty($data['unit_price']) ? '0' : $data['unit_price'];

        //手续费 = 营业额*系统设置比例
        $data['formalities_cost'] = max(0,bcmul($data['turnover'],($data['service_charge']*0.01),2));

        //产品总成本 = 所有SKU成本 + (付款订单数*挂号费) + (产品总重量 * 每克运费)
        $data['product_total_cost'] = $data['sku_total_cost']+$data['freight_sum']+bcmul($data['paid_orders'],$data['register_cost'],2)+bcmul($data['total_weight'],$data['freight'],2);

        return $data;
    }

    /**
     * 更新某个店铺某个时间的运营数据
     * @param array $data
     * @return array
     */
    public function common_operate_used($data = []){

        //获取店铺指定时间的总营业额,付款订单数,付款订单id,运费 (状态为已支付)
        $sql = "SELECT SUM(total_price_usd) AS turnover,count(shopify_o_id) AS paid_orders,
                            SUM(freight) AS freight_sum FROM `order` WHERE
                           shop_id = {$data['shop_id']} AND datetime = '{$data['datetime']}' AND financial_status = 'paid'";
        $orderSum =$this->db->query ( $sql )->row_array ();
        $data['turnover'] = max(0,$orderSum['turnover']);//店铺总营业额
        $data['paid_orders'] = max(0,$orderSum['paid_orders']);//付款订单数
        $data['freight_sum'] = max(0,$orderSum['freight_sum']);//运费

        if(empty($data['org_id'])){ //没有部门的时候获取部门
            //根据user_id获取部门id
            $org_id = $this->db->query ( "select org_id from admin where id = {$data['user_id']}" )->row_array ()['org_id'];
            if(empty($org_id)){ //当员工没有部门的时候
                $data['operate_remark'] = '该用户没有相应部门';
                return $data;
            }
        }

        //没有提成规则的时候，重新获取提成规则
        if(empty($data['exchange_rate']) ||empty($data['freight']) ||empty($data['service_charge']) ||empty($data['register_fee'])){
            //根据部门id获取相应的提成规则的手续费(百分比),汇率，每克运费，挂号费
            $fees = $this->royalty_rules_data->find(['o_id'=>$org_id]);
            if(empty($fees)){
                $data['operate_remark'] = '缺少提成规则';
                return $data;
            }
        }

        $data['exchange_rate'] = max(0,$fees['exchange_rate']);//汇率
        $data['freight'] = max(0,$fees['freight']);//每克运费
        $data['service_charge'] = max(0,$fees['service_charge']);//手续费 %
        $data['register_cost'] = max(0,$fees['register_fee']);//挂号费

        //获取对应的sku_总成本,商品总重量,sku是否存在标识
        $cost = $this->get_cost($data);
        if($cost===2){
            $data['operate_remark'] = '请填写sku信息';
            return $data;
        }
        if($cost===1){
            $data['operate_remark'] = '该店铺有sku没有匹配成功';
            return $data;
        }
        $data['sku_total_cost'] = $cost['sku_total_cost'];//sku总成本(人民币)
        $data['total_weight'] = $cost['total_weight']; //sku商品总重量(g)

        //客单价 = 营业额/付款订单数
        $data['unit_price'] = $data['turnover']?bcdiv($data['turnover'],$data['paid_orders'],2):0;
        $data['unit_price'] = empty($data['unit_price']) ? '0' : $data['unit_price'];

        //手续费 = 营业额*系统设置比例
        $data['formalities_cost'] = max(0,bcmul($data['turnover'],($data['service_charge']*0.01),2));

        //产品总成本 = 所有SKU成本 + (付款订单数*挂号费) + (产品总重量 * 每克运费)
        $data['product_total_cost'] = $data['sku_total_cost']+$data['freight_sum']+bcmul($data['paid_orders'],$data['register_cost'],2)+bcmul($data['total_weight'],$data['freight'],2);

        //有广告费的时候，计算毛利率等数据
        if($data['ad_cost']){
            $data = $this->get_sum_rate($data);
        }
        return $data;
    }

    /**
     * 获取某个店铺某天的sku总成本
     * @param array
     * $data
     * @return bool
     */
    public function get_cost($data = []){

        //所有订单
        $sql = "select c.sku_id,SUM(c.quantity) as quantity,SUM(c.quantity*c.countfreight) as countfreight  from (SELECT
                b.sku_id,
                sum(b.quantity) AS quantity,
                count(a.freight) AS countfreight
            FROM
                `order` a
            RIGHT JOIN order_goods b ON a.id = b.o_id
            WHERE
                b.shop_id = {$data['shop_id']}
            AND b.datetime = '".$data['datetime']."'
            GROUP BY
                b.sku_id,a.freight) c GROUP BY c.sku_id";
        $sku_list = $this->db->query ( $sql )->result_array ();
        $sku_list_sum = model('data/order_goods_data')->split_sku_comm($sku_list);

        foreach($sku_list_sum as $k=>$v){
            if(empty($k)){ //sku_id为空时 跳出
                return 2;
            }

            $sql = "select price,weight from goods_sku where  code in ('".$k."') or alias REGEXP '(^|,)(".$k.")(,|$)' GROUP BY code";
            $sku_info = $this->db->query ( $sql )->row_array ();

            if(!$sku_info){ //没有查到对应sku信息时，跳出
                return 1;
            }
            $sku_list_sum[$k]['product_cost'] = $sku_info['price']*$v['quantity'];
            $sku_list_sum[$k]['weight'] = $sku_info['weight']*($v['quantity']-$v['countfreight']);
        }

        //计算总和
        $info['sku_total_cost'] = array_sum(array_column($sku_list_sum,'product_cost')); //总价格
        $info['total_weight'] = array_sum(array_column($sku_list_sum,'weight')); //总重量

        return $info;
    }

    /**
     * 计算利率等数据
     * @param array $data
     * @return array
     */
    public function get_sum_rate($data = []){

        //上传广告费后,计算相应数据
        $data['ROI'] = $data['turnover'] && $data['ad_cost']?bcdiv($data['turnover'],$data['ad_cost'],2):0;//ROI=营业额/广告费

        //每单广告成本=广告费/付款订单数
        $data['unit_ad_cost'] = $data['ad_cost'] && $data['paid_orders']?bcdiv($data['ad_cost'],$data['paid_orders'],2):0;

        //产品总成本转换为美元
        $product_total_cost_usd = $data['product_total_cost'] && $data['exchange_rate']? bcdiv($data['product_total_cost'],$data['exchange_rate'],2):0;

        //毛利=营业额-广告费-手续费-产品总成本(美元)
        $data['gross_profit'] = $data['turnover']-$data['ad_cost']-$data['formalities_cost']-$product_total_cost_usd;

        //毛利(人民币)
        $data['gross_profit_rmb'] = bcmul($data['gross_profit'],$data['exchange_rate'],2);

        //毛利率=毛利/营业额
        $data['gross_profit_rate'] = $data['gross_profit'] && (float)$data['turnover']?bcdiv($data['gross_profit'],$data['turnover'],9):0;

        return $data;
    }

}

