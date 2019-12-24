<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/20
 * Time: 9:59
 */

class Getoperate_data extends \Application\Component\Common\IData
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
        $shop = $this->db->query ( "select id from shop" )->result_array ();
        $shops = [];
        foreach ($shop as $v){
            array_push($shops,$v['id']);
        }
        //获取前一天的日期
        $date = date("Y-m-d",strtotime("-1 day"));
        //循环将每个店铺前一天的运营数据写入到本地数据库中
        foreach ($shops as $shop_id){
            //获取店铺前一天的总营业额,付款订单数,付款订单id (状态为已支付)
            $sql = "SELECT SUM(total_price_usd) AS turnover,count(id) AS paid_orders,GROUP_CONCAT(id) AS orders FROM `order` WHERE  shop_id = $shop_id AND updated_at LIKE '{$date}%' AND financial_status = 'paid'";
            $data =$this->db->query ( $sql )->row_array ();
            $data['turnover'] = empty($data['turnover']) ? '0' : $data['turnover'];//店铺总营业额
            $data['paid_orders'] = empty($data['paid_orders']) ? '0' : $data['paid_orders'];//付款订单数
            $orders = $data['orders'];
            //根据付款订单id,获取对应的sku_总成本,商品总重量
            if(!empty($orders)){
                $sql_o = "SELECT sum(a.quantity*b.weight) as total_weight,sum(a.quantity*b.price) as sku_total_cost FROM order_goods a LEFT JOIN goods_sku b on a.sku_id = b.code WHERE o_id in ( $orders )";
                $goods = $this->db->query ( $sql_o )->row_array ();
                $data['sku_total_cost'] = empty($goods['sku_total_cost']) ? '0' : $goods['sku_total_cost'];//所有SKU成本
                $data['total_weight'] = empty($goods['total_weight']) ? '0' : $goods['total_weight'];//商品总重量
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
            //根据部门id获取相应的提成规则的手续费(百分比),挂号费
            $sql = "select service_charge,register_fee from royalty_rules where o_id in ($org_id) and type = 1";
            $fees = $this->db->query ( $sql )->row_array ();
            $service_charge = $fees['service_charge'];//手续费(百分比)
            $register_cost = empty($fees['register_fee']) ? '0' : $fees['register_fee'];//挂号费
            //手续费 = 营业额*系统设置比例
            $formalities_cost = bcmul($data['turnover'],($service_charge*0.01),2);
            //产品总成本 = 所有SKU成本 + 付款订单数*挂号费
            $product_total_cost = bcadd($data['sku_total_cost'],($data['paid_orders']*$register_cost),2);
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
                'product_total_cost' => $product_total_cost
            );
            $this->operate_data->store($data);
        }
    }
}