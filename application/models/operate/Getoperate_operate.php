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
        $this->load->model ( 'data/operate_data' );
    }

    //根据店铺获取前天的运营数据存入数据库
    public function get_datas()
    {
        set_time_limit(0);
        //获取当前所有店铺
        $shops = $this->db->query ( "select id from shop" )->result_array ();

        //没有店铺时 跳出程序
        if(empty($shops)){
            return false;
        }
        //获取前天的日期
        $date = date("Y-m-d",strtotime("-2 day"));
        $time = date('Y-m-d H:i:s');

        //循环将每个店铺前天的运营数据写入到本地数据库中
        foreach ($shops as $v){

            $data = [];//初始化数组
            $data['datetime'] = $date;
            $data['insert_time'] = $time;

            //店铺id
            $data['shop_id'] = $v['id'];

            //根据shop_id 获取user_id
            $data['user_id'] = $this->db->query ( "SELECT user_id FROM shop where id = {$data['shop_id']}" )->row_array ()['user_id'];
            //根据user_id获取部门id
            $data['org_id'] = $this->db->query ( "select org_id from admin where id = {$data['user_id']}" )->row_array ()['org_id'];

            //根据部门id获取相应的提成规则的手续费(百分比),汇率
            $fees = [];
            if($data['org_id']){
                $sql = "select service_charge,exchange_rate from royalty_rules where o_id in ({$data['org_id']}) and type = 1";
                $fees = $this->db->query ( $sql )->row_array ();
            }
            $fees = $this->db->query ( $sql )->row_array ();
            if(empty($fees)){
                $data['operate_remark']  = '缺少提成规则';
            }
            else{
                //获取店铺前天的总营业额,付款订单数,付款订单id,运费 (状态为已支付)
                $sql = "SELECT SUM(total_price_usd) AS turnover,count(shopify_o_id) AS paid_orders,
                        SUM(freight) AS freight_sum FROM `order` WHERE
                       shop_id = {$data['shop_id']} AND datetime = '$date' AND financial_status = 'paid'";
                $orderSum =$this->db->query ( $sql )->row_array ();
                $data['turnover'] = max(0,$orderSum['turnover']);//店铺总营业额
                $data['paid_orders'] = max(0,$orderSum['paid_orders']);//付款订单数
                $data['freight_sum'] = max(0,$orderSum['freight_sum']);//总运费

                //获取对应的sku_总成本,商品总重量,sku是否存在标识
                $cost = $this->get_cost($data['shop_id'],$date);
                $data['sku_total_cost'] = $cost['sku_total_cost'];
                $data['total_weight'] = $cost['total_weight'];
                $isset_sku = $cost['isset_sku'];

                //客单价 = 营业额/付款订单数
                $data['unit_price'] = $data['turnover']?bcdiv($data['turnover'],$data['paid_orders'],2):0;
                $data['unit_price'] = empty($data['unit_price']) ? '0' : $data['unit_price'];

                //手续费(百分比)
                $service_charge = empty($fees['service_charge']) ? '0' : $fees['service_charge'];
                //挂号费
                $register_cost = empty($fees['register_fee']) ? '0' : $fees['register_fee'];
                //汇率
                $exchange_rate = empty($fees['exchange_rate']) ? '0' : $fees['exchange_rate'];

                //每克运费
                $freight = empty($fees['freight']) ? '0' : $fees['freight'];

                //手续费 = 营业额*系统设置比例
                $formalities_cost = max(0,bcmul($data['turnover'],($service_charge*0.01),2));

                //产品总成本 = 所有SKU成本 + 总运费
                $product_total_cost = $data['sku_total_cost'] + $data['freight_sum'];
                $product_total_cost = empty($product_total_cost) ? '0' : $product_total_cost;
                //写入数据库的数据
                if($isset_sku == 1){
                    $data = array(
                        'datetime' => $date,
                        'shop_id' => $data['shop_id'],
                        'user_id' => $data['user_id'],
                        'paid_orders' => $data['paid_orders'],
                        'turnover' => $data['turnover'],
                        'total_weight' => '0',
                        'sku_total_cost' => '0',
                        'unit_price' => '0',
                        'formalities_cost' => '0',
                        'register_cost' => $register_cost,
                        'product_total_cost' => '0',
                        'exchange_rate' => $exchange_rate,
                        'freight_sum' => $data['freight_sum'],
                        'freight' => $freight,
                        'service_charge' => $service_charge,
                        'operate_remark' => '缺少sku信息！',
                        'insert_time' => date('Y-m-d h:i:s', time())
                    );
                }else{
                    $data = array(
                        'datetime' => $date,
                        'shop_id' => $data['shop_id'],
                        'user_id' => $data['user_id'],
                        'paid_orders' => $data['paid_orders'],
                        'turnover' => $data['turnover'],
                        'total_weight' => $data['total_weight'],
                        'sku_total_cost' => $data['sku_total_cost'],
                        'unit_price' => $data['unit_price'],
                        'formalities_cost' => $formalities_cost,
                        'register_cost' => $register_cost,
                        'product_total_cost' => $product_total_cost,
                        'freight_sum' => $data['freight_sum'],
                        'exchange_rate' => $exchange_rate,
                        'freight' => $freight,
                        'service_charge' => $service_charge,
                        'insert_time' => date('Y-m-d h:i:s', time())
                    );
                }

            }

            //将数据存入数据库
            $this->operate_data->store($data);
        }
    }

    //同步某个店铺某天的运营数据
    public function get_data( $shop_id , $date )
    {
        set_time_limit(0);
        $today = date("Y-m-d");//今天的日期
        if($shop_id && $date && $date < $today){
            //获取某个店铺某天的总营业额,总快递费,付款订单数,付款订单id (状态为已支付)
            $sql = "SELECT SUM(total_price_usd) AS turnover,SUM(freight) AS freight_sum
                    ,count(shopify_o_id) AS paid_orders  FROM `order` WHERE
                    shop_id = $shop_id AND datetime = '$date' AND financial_status = 'paid'";
            $data =$this->db->query ( $sql )->row_array ();
            $data['turnover'] = max(0,$data['turnover']);//店铺总营业额
            $data['paid_orders'] = max(0,$data['paid_orders']);//付款订单数

            //根据付款订单id,获取对应的sku_总成本
            $cost = $this->get_cost($shop_id,$date);

            $data['sku_total_cost'] = max(0,$cost['sku_total_cost']); //SKU总成本
            $data['product_total_cost'] = $data['sku_total_cost']+$data['freight_sum'];//产品总成本

            $isset_sku = $cost['isset_sku'];

            //客单价 = 营业额/付款订单数
            $data['unit_price'] = $data['turnover']?bcdiv($data['turnover'],$data['paid_orders'],2):0;

            //根据shop_id和datetime查出已有的运营数据
            $operate = $this->db->query ( "SELECT * FROM operate WHERE shop_id = $shop_id AND datetime = '$date'" )->row_array ();

            if($operate){
                //获取汇率
                if(!$operate['exchange_rate'] || !$operate['service_charge']){ //未记录汇率或手续费，重新获取规则

                    $fees = $this->db->query ( "select exchange_rate,service_charge from royalty_rules where type=1 and o_id = (select org_id from admin where id = {$operate['user_id']})" )->row_array ();
                    $operate['exchange_rate'] = $fees['exchange_rate']; //汇率
                    $operate['service_charge'] = $fees['service_charge']; //手续费
                }

                if(empty($operate['exchange_rate']) || empty($operate['service_charge'])){
                    $data = array(
                        'datetime' => $date,
                        'shop_id' => $shop_id,
                        'user_id' => $operate['user_id'],
                        'paid_orders' =>$data['paid_orders'],
                        'turnover' => $data['turnover'],
                        'total_weight' => '0',
                        'sku_total_cost' => '0',
                        'unit_price' => '0',
                        'formalities_cost' =>'0',
                        'freight_sum' =>'0',
                        'service_charge' =>'0',
                        'product_total_cost' => '0',
                        'operate_remark' => '缺少提成规则！',
                        'insert_time' => date('Y-m-d h:i:s', time())
                    );
                }
                else{

                    //手续费
                    $formalities_cost = max(0,bcmul($data['turnover'],($operate['service_charge']*0.01),2));

                    //判断是否已经上传广告费用
                    if(empty($operate['ad_cost'])){//广告费未上传时,数据为空
                        $ad_cost = null;
                        $ROI = null;
                        $unit_ad_cost = null;
                        $gross_profit = null;
                        $gross_profit_rmb = null;
                        $gross_profit_rate = null;
                        $review_status = 0;
                    }else{//上传广告费后,计算相应数据

                        //ROI=营业额/广告费
                        $ROI = $data['turnover']&&$operate['ad_cost'] ? bcdiv($data['turnover'],$operate['ad_cost'],2):0;
                        $ROI = max(0,$ROI);

                        //每单广告成本=广告费/付款订单数
                        $unit_ad_cost = $operate['ad_cost'] && $data['paid_orders'] ? bcdiv($operate['ad_cost'],$data['paid_orders'],2):0;
                        $unit_ad_cost = max(0,$unit_ad_cost);

                        //产品总成本转换为美元
                        $product_total_cost_usd = $data['sku_total_cost']&&$operate['exchange_rate'] ? bcdiv($data['sku_total_cost'],$operate['exchange_rate'],2):0;
                        $product_total_cost_usd = max(0,$product_total_cost_usd);

                        //毛利=营业额-广告费-运费-手续费-产品总成本(美元)
                        $gross_profit = $data['turnover']-$operate['ad_cost']-$data['freight_sum']-$product_total_cost_usd;

                        //毛利(人民币)
                        $gross_profit_rmb = max(0,bcmul($gross_profit,$operate['exchange_rate'],2));

                        //毛利率=毛利/营业额
                        $gross_profit_rate = $gross_profit && $data['turnover'] ? bcdiv($gross_profit,$data['turnover'],9):0;
                        $gross_profit_rate = max(0,$gross_profit_rate);

                        $review_status = 1;
                    }
                    //需要修改的数据
                    if($isset_sku == 1){
                        $data = array(
                            'paid_orders' => $data['paid_orders'],
                            'turnover' => $data['turnover'],
                            'total_weight' => '0',
                            'sku_total_cost' => '0',
                            'unit_price' => '0',
                            'product_total_cost' => '0',
                            'ad_cost' =>  null,
                            'review_status' => '0',
                            'review_time' => null,
                            'reviewer' => null,
                            'ROI' => null,
                            'unit_ad_cost' => null,
                            'gross_profit' => null,
                            'gross_profit_rmb' => null,
                            'formalities_cost' => $formalities_cost,
                            'gross_profit_rate' => null,
                            'exchange_rate' => $operate['exchange_rate'],
                            'service_charge' => $operate['service_charge'],
                            'freight_sum' => $data['freight_sum'],
                            'operate_remark' => '缺少sku信息！',
                            'insert_time' => date('Y-m-d h:i:s', time())
                        );
                    }else{
                        $data = array(
                            'paid_orders' => $data['paid_orders'],
                            'turnover' => $data['turnover'],
                            'total_weight' => $data['total_weight'],
                            'sku_total_cost' => $data['sku_total_cost'],
                            'unit_price' => $data['unit_price'],
                            'product_total_cost' => $data['product_total_cost'],
                            'ad_cost' => $operate['ad_cost'],
                            'review_status' => $review_status,
                            'review_time' => null,
                            'reviewer' => null,
                            'ROI' => $ROI,
                            'formalities_cost' => $formalities_cost,
                            'unit_ad_cost' => $unit_ad_cost,
                            'gross_profit' => $gross_profit,
                            'gross_profit_rmb' => $gross_profit_rmb,
                            'gross_profit_rate' => $gross_profit_rate,
                            'operate_remark' => null,
                            'freight_sum' => $data['freight_sum'],
                            'exchange_rate' => $operate['exchange_rate'],
                            'service_charge' => $operate['service_charge'],
                            'insert_time' => date('Y-m-d h:i:s', time())
                        );
                    }
                }

                //更新数据到数据库
                $this->operate_data->update($operate['id'],$data);
            }
            else{

                $user_id  = $this->db->query("SELECT user_id from shop where id={$shop_id}")->row_array()['user_id'];

                $fees =  $this->db->query("select exchange_rate,service_charge from royalty_rules where type=1 and
                                            o_id = (select org_id from admin where id = {$user_id})")->row_array();

                if(empty($fees['exchange_rate']) || empty($fees['service_charge'])){
                    $data = array(
                        'datetime' => $date,
                        'shop_id' => $shop_id,
                        'user_id' => $user_id,
                        'paid_orders' => $data['paid_orders'],
                        'turnover' => $data['turnover'],
                        'total_weight' => '0',
                        'sku_total_cost' => $data['sku_total_cost'],
                        'unit_price' => '0',
                        'freight_sum' => $data['freight_sum'],
                        'product_total_cost' => '0',
                        'operate_remark' => '缺少提成规则！',
                        'insert_time' => date('Y-m-d h:i:s', time())
                    );
                }else{

                    //写入数据库的数据
                    if($isset_sku == 1){
                        $data = array(
                            'datetime' => $date,
                            'shop_id' => $shop_id,
                            'user_id' => $user_id,
                            'paid_orders' => $data['paid_orders'],
                            'turnover' => $data['turnover'],
                            'total_weight' => '0',
                            'sku_total_cost' => $data['sku_total_cost'],
                            'unit_price' => '0',
                            'product_total_cost' => '0',
                            'exchange_rate' => $fees['exchange_rate'],
                            'service_charge' => $fees['service_charge'],
                            'freight_sum' => $data['freight_sum'],
                            'operate_remark' => '缺少sku信息！',
                            'insert_time' => date('Y-m-d h:i:s', time())
                        );
                    }else{

                        //手续费
                        $formalities_cost = max(0,bcmul($data['turnover'],($fees['service_charge']*0.01),2));

                        $data = array(
                            'datetime' => $date,
                            'shop_id' => $shop_id,
                            'user_id' => $user_id,
                            'paid_orders' => $data['paid_orders'],
                            'turnover' => $data['turnover'],
                            'total_weight' => $data['total_weight'],
                            'sku_total_cost' => $data['sku_total_cost'],
                            'unit_price' => $data['unit_price'],
                            'formalities_cost' => $formalities_cost,
                            'product_total_cost' => $data['product_total_cost'],
                            'exchange_rate' => $fees['exchange_rate'],
                            'service_charge' => $fees['service_charge'],
                            'freight_sum' => $data['freight_sum'],
                            'insert_time' => date('Y-m-d h:i:s', time())
                        );
                    }
                }
                    //将数据存入数据库
                    $this->operate_data->store($data);
                }
            }
    }


    //获取某个店铺某天的sku_总成本,是否存在sku标识(0存在,1不存在)
    public function get_cost($shop_id,$date){

        $info = [];

        $sql = "select sku_id,quantity from order_goods WHERE shop_id = $shop_id AND datetime = '$date'";
        $sku_list = $this->db->query ( $sql )->result_array ();
        $sku_list_sum = model('data/order_goods_data')->split_sku_comm($sku_list);


        foreach($sku_list_sum as $k=>$v){

            $sql = "select price from goods_sku where  code in ('".$k."') or alias REGEXP '(^|,)(".$k.")(,|$)' GROUP BY code";
            $sku_price = $this->db->query ( $sql )->row_array ();

            if(!$sku_price){
                $info['isset_sku'] =  1;
                return $info;
            }
            $sku_list_sum[$k]['product_cost'] = $sku_price['price']*$v['quantity'];
        }

        //计算总和
        $info['sku_total_cost'] = array_sum(array_column($sku_list_sum,'product_cost'));

        return $info;

    }

    //获取某个产品的单价和重量
    public function get_detail($sku){
        $sql = "select code,price,weight from goods_sku where code = '$sku'";
        $detail = $this->db->query ( $sql )->row_array ();
        if(!$detail){
            $sql = "select alias,price,weight from goods_sku where alias = '$sku'";
            $detail = $this->db->query ( $sql )->row_array ();
        }
        return $detail;
    }
}

