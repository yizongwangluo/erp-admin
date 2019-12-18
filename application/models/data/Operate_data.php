<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/6
 * Time: 13:42
 */

class Operate_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();

    }

    public function index( $admin_id ){
        if($admin_id == 1){
            $sql = "SELECT
	a.*, b.real_name,
	c.domain,
	d.real_name AS reviewer_name
FROM
	operate a
LEFT JOIN admin b ON a.user_id = b.id
LEFT JOIN shop c ON a.shop_id = c.id
LEFT JOIN admin d ON a.reviewer = d.id";
        }
        else{
            $sql = "SELECT
	a.*, b.real_name,
	c.domain,
	d.real_name AS reviewer_name
FROM
	operate a
INNER JOIN (
	SELECT
		s_u_id,
		s_real_name AS real_name
	FROM
		admin_org_temp
	WHERE
		u_id = $admin_id
	GROUP BY
		s_u_id
) b ON a.user_id = b.s_u_id
LEFT JOIN shop c ON a.shop_id = c.id
LEFT JOIN admin d ON a.reviewer = d.id";
        }
        return $sql;
    }

    public function add( array $in )
    {
        $id = $in['id'];
        $shop_id = $in['shop_id'];
        $date = strtotime($in['date']);
        $count = $this->db->query ( "select count(*) as count from operate where shop_id = $shop_id and date = $date" )->row_array ()['count'];
        if($count > 0){
            $this->set_error(' 该店铺已存在当前日期数据！');
            return false;
        }
        $user_id = $this->db->query ( "SELECT user_id FROM shop where id = $shop_id" )->row_array ()['user_id'];
        $org_id = $this->db->query ( "select org_id from admin where id = $user_id" )->row_array ()['org_id'];
        $sql = "select service_charge,register_fee,exchange_rate from royalty_rules where o_id in ($org_id) and type = 1";
        $fees = $this->db->query ( $sql )->row_array ();
        $service_charge = $fees['service_charge'];
        $register_cost = $fees['register_fee'];
        $exchange_rate = $fees['exchange_rate'];

        $unit_price = bcdiv($in['turnover'],$in['paid_orders'],2);
        $formalities_cost = bcmul($in['turnover'],($service_charge*0.01),2);
        $product_total_cost = bcadd($in['sku_total_cost'],($in['paid_orders']*$register_cost),2);

        if(empty($in['ad_cost'])){
            $ROI = '';
            $unit_ad_cost = '';
            $gross_profit = '';
            $gross_profit_rate = '';
            $in['review_status'] = 0;
        }else{
            $ROI = bcdiv($in['turnover'],$in['ad_cost'],2);
            $unit_ad_cost = bcdiv($in['ad_cost'],$in['paid_orders'],2);
            $gross_profit = $in['turnover']-$in['ad_cost']-$formalities_cost-bcdiv($product_total_cost,$exchange_rate,2);
            $gross_profit_rate = bcdiv($gross_profit,$in['turnover'],9);
            $in['review_status'] ? $in['review_status']=$in['review_status'] : $in['review_status'] = 1;
        }

        $data = array(
            'date' => strtotime($in['date']),
            'shop_id' => $in['shop_id'],
            'user_id' => $user_id,
            'paid_orders' => $in['paid_orders'],
            'turnover' => $in['turnover'],
            'sku_total_cost' => $in['sku_total_cost'],
            'ad_cost' => $in['ad_cost'],
            'review_status' => $in['review_status'],
            'review_time' => $in['review_time'],
            'reviewer' => $in['reviewer'],
            'unit_price' => $unit_price,
            'formalities_cost' => $formalities_cost,
            'register_cost' => $register_cost,
            'product_total_cost' => $product_total_cost,
            'ROI' => $ROI,
            'unit_ad_cost' => $unit_ad_cost,
            'gross_profit' => $gross_profit,
            'gross_profit_rate' => $gross_profit_rate

        );

        function  filtrfunction($arr){
            if($arr === '' || $arr === null){
                return false;
            }
            return true;
        }

        if (!$id) {
            $data = array_filter($data,'filtrfunction');
            if (!$this->store($data)) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
        }else{
            unset($in['id']);
            if (!$this->operate_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

    public function get_domains( $admin_id )
    {
        if($admin_id == 1){
            $sql = "select id,domain,user_id from shop";
        }else{
            $sql = "SELECT a.id,a.domain,a.user_id FROM shop a INNER JOIN (SELECT s_u_id FROM admin_org_temp WHERE u_id = $admin_id GROUP BY s_u_id) b ON a.user_id = b.s_u_id";
        }
        $domains = $this->db->query ( $sql )->result_array ();
        return $domains;
    }
}