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

    //根据店铺获取前一天的运营数据存入数据库
    public function get_datas()
    {
        set_time_limit(0);
        //获取当前所有店铺
        $shops = $this->db->query ( "select id from shop" )->result_array ();
        //没有店铺时 跳出程序
        if(empty($shops)){
            return false;
        }
        //获取前一天的日期
        $date = date("Y-m-d",strtotime("-1 day"));
        //循环将每个店铺前一天的运营数据写入到本地数据库中
        foreach ($shops as $v){
            //店铺id
            $shop_id = $v['id'];
            //获取店铺前一天的总营业额,付款订单数,付款订单id (状态为已支付)
            $sql = "SELECT SUM(total_price_usd) AS turnover,count(shopify_o_id) AS paid_orders,GROUP_CONCAT(shopify_o_id) AS orders FROM `order` WHERE  shop_id = $shop_id AND datetime = '$date' AND financial_status = 'paid'";
            $data =$this->db->query ( $sql )->row_array ();
            $data['turnover'] = empty($data['turnover']) ? '0' : $data['turnover'];//店铺总营业额
            $data['paid_orders'] = empty($data['paid_orders']) ? '0' : $data['paid_orders'];//付款订单数
            $orders = $data['orders'];
            //根据付款订单id,获取对应的sku_总成本,商品总重量
            if(!empty($orders)){
                $sql_o = "SELECT sum(a.quantity*b.weight) as total_weight,sum(a.quantity*b.price) as sku_total_cost FROM order_goods a LEFT JOIN goods_sku b on a.sku_id = b.code WHERE shop_id = $shop_id AND shopify_o_id in ( $orders )";
                $goods = $this->db->query ( $sql_o )->row_array ();
                //所有SKU成本
                $data['sku_total_cost'] = empty($goods['sku_total_cost']) ? '0' : $goods['sku_total_cost'];
                //商品总重量
                $data['total_weight'] = empty($goods['total_weight']) ? '0' : $goods['total_weight'];
            }else{
                $data['sku_total_cost'] = '0';
                $data['total_weight'] = '0';
            }
            //根据shop_id 获取user_id
            $user_id = $this->db->query ( "SELECT user_id FROM shop where id = $shop_id" )->row_array ()['user_id'];
            //客单价 = 营业额/付款订单数
            $data['unit_price'] = bcdiv($data['turnover'],$data['paid_orders'],2);
            $data['unit_price'] = empty($data['unit_price']) ? '0' : $data['unit_price'];
            //根据user_id获取部门id
            $org_id = $this->db->query ( "select org_id from admin where id = $user_id" )->row_array ()['org_id'];
            //根据部门id获取相应的提成规则的手续费(百分比),挂号费,汇率,每克运费
            $sql = "select service_charge,register_fee,exchange_rate,freight from royalty_rules where o_id in ($org_id) and type = 1";
            $fees = $this->db->query ( $sql )->row_array ();
            $service_charge = empty($fees['service_charge']) ? '0' : $fees['service_charge'];//手续费(百分比)
            $register_cost = empty($fees['register_fee']) ? '0' : $fees['register_fee'];//挂号费
            $exchange_rate = empty($fees['exchange_rate']) ? '0' : $fees['exchange_rate'];//汇率
            $freight = empty($fees['freight']) ? '0' : $fees['freight'];//每克运费
            //手续费 = 营业额*系统设置比例
            $formalities_cost = bcmul($data['turnover'],($service_charge*0.01),2);
            $formalities_cost = empty($formalities_cost) ? '0' : $formalities_cost;
            //产品总成本 = 所有SKU成本 + 付款订单数*挂号费 + 产品总重量 * 每克运费
            $product_total_cost = $data['sku_total_cost']+bcmul($data['paid_orders'],$register_cost,2)+bcmul($data['total_weight'],$freight,2);
            $product_total_cost = empty($product_total_cost) ? '0' : $product_total_cost;
            //写入数据库的数据
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
                'register_cost' => $register_cost,
                'product_total_cost' => $product_total_cost,
                'exchange_rate' => $exchange_rate,
                'freight' => $freight,
                'service_charge' => $service_charge
            );
            //将数据存入数据库
            $this->operate_data->store($data);
        }
    }

    //同步某个店铺某天的运营数据
    public function get_data($shop_id, $date)
    {
        set_time_limit(0);
        $today = date("Y-m-d");//今天的日期
        if(!empty($shop_id) && !empty($date) && $date < $today){
            //获取某个店铺某天的总营业额,付款订单数,付款订单id (状态为已支付)
            $sql = "SELECT SUM(total_price_usd) AS turnover,count(shopify_o_id) AS paid_orders,GROUP_CONCAT(shopify_o_id) AS orders FROM `order` WHERE  shop_id = $shop_id AND datetime = '$date' AND financial_status = 'paid'";
            $data =$this->db->query ( $sql )->row_array ();
            $data['turnover'] = empty($data['turnover']) ? '0' : $data['turnover'];//店铺总营业额
            $data['paid_orders'] = empty($data['paid_orders']) ? '0' : $data['paid_orders'];//付款订单数
            $orders = $data['orders'];
            //根据付款订单id,获取对应的sku_总成本,商品总重量
            if(!empty($orders)){
                $sql_o = "SELECT sum(a.quantity*b.weight) as total_weight,sum(a.quantity*b.price) as sku_total_cost FROM order_goods a LEFT JOIN goods_sku b on a.sku_id = b.code WHERE shop_id = $shop_id AND shopify_o_id in ( $orders )";
                $goods = $this->db->query ( $sql_o )->row_array ();
                $data['sku_total_cost'] = empty($goods['sku_total_cost']) ? '0' : $goods['sku_total_cost'];//所有SKU成本
                $data['total_weight'] = empty($goods['total_weight']) ? '0' : $goods['total_weight'];//商品总重量
            }else{
                $data['sku_total_cost'] = '0';
                $data['total_weight'] = '0';
            }
            //客单价 = 营业额/付款订单数
            $data['unit_price'] = bcdiv($data['turnover'],$data['paid_orders'],2);
            $data['unit_price'] = empty($data['unit_price']) ? '0' : $data['unit_price'];
            //根据shop_id和datetime查出已有的运营数据
            $operate = $this->db->query ( "SELECT * FROM operate WHERE shop_id = $shop_id AND datetime = '$date'" )->row_array ();
            //手续费 = 营业额*系统设置比例
            $formalities_cost = bcmul($data['turnover'],($operate['service_charge']*0.01),2);
            $formalities_cost = empty($formalities_cost) ? '0' : $formalities_cost;
            //产品总成本 = 所有SKU成本 + 付款订单数*挂号费 + 产品总重量 * 每克运费
            $product_total_cost = $data['sku_total_cost']+bcmul($data['paid_orders'],$operate['register_cost'],2)+bcmul($data['total_weight'],$operate['freight'],2);
            $product_total_cost = empty($product_total_cost) ? '0' : $product_total_cost;
            //判断是否已经上传广告费用
            if(empty($operate['ad_cost'])){//广告费未上传时,数据为空
                $ad_cost = '';
                $ROI = '';
                $unit_ad_cost = '';
                $gross_profit = '';
                $gross_profit_rmb = '';
                $gross_profit_rate = '';
                $review_status = 0;
            }else{//上传广告费后,计算相应数据
                $ad_cost = $operate['ad_cost'];
                $ROI = bcdiv($data['turnover'],$operate['ad_cost'],2);//ROI=营业额/广告费
                //每单广告成本=广告费/付款订单数
                $unit_ad_cost = bcdiv($operate['ad_cost'],$data['paid_orders'],2);
                $unit_ad_cost = empty($unit_ad_cost) ? '0' : $unit_ad_cost;
                //产品总成本转换为美元
                $product_total_cost_usd = bcdiv($product_total_cost,$operate['exchange_rate'],2);
                $product_total_cost_usd = empty($product_total_cost_usd) ? '0' : $product_total_cost_usd;
                //毛利=营业额-广告费-手续费-产品总成本(美元)
                $gross_profit = $data['turnover']-$operate['ad_cost']-$formalities_cost-$product_total_cost_usd;
                //毛利(人民币)
                $gross_profit_rmb = bcmul($gross_profit,$operate['exchange_rate'],2);
                $gross_profit_rmb = empty($gross_profit_rmb) ? '0' : $gross_profit_rmb;
                //毛利率=毛利/营业额
                $gross_profit_rate = bcdiv($gross_profit,$data['turnover'],9);
                $gross_profit_rate = empty($gross_profit_rate) ? '0' : $gross_profit_rate;
                $review_status = 1;
            }
            //需要修改的数据
            $data = array(
                'paid_orders' => $data['paid_orders'],
                'turnover' => $data['turnover'],
                'total_weight' => $data['total_weight'],
                'sku_total_cost' => $data['sku_total_cost'],
                'unit_price' => $data['unit_price'],
                'formalities_cost' => $formalities_cost,
                'product_total_cost' => $product_total_cost,
                'ad_cost' => $ad_cost,
                'review_status' => $review_status,
                'review_time' => '',
                'reviewer' => '',
                'ROI' => $ROI,
                'unit_ad_cost' => $unit_ad_cost,
                'gross_profit' => $gross_profit,
                'gross_profit_rmb' => $gross_profit_rmb,
                'gross_profit_rate' => $gross_profit_rate
            );
            //过滤掉值为''和null的数据
            function  filtrfunction($arr){
                if($arr === '' || $arr === null){
                    return false;
                }
                return true;
            }
            $data = array_filter($data,'filtrfunction');
            //更新数据到数据库
            $this->operate_data->update($operate['id'],$data);
        }

    }
}