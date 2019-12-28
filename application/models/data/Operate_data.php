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
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain,
			d.user_name AS reviewer_name
		FROM
			operate a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN admin d ON a.reviewer = d.id
	) s";
        }
        else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain,
			d.user_name AS reviewer_name
		FROM
			operate a
		INNER JOIN (
			SELECT
				s_u_id,
				s_user_name AS user_name
			FROM
				admin_org_temp
			WHERE
				u_id = $admin_id
			GROUP BY
				s_u_id
		) b ON a.user_id = b.s_u_id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN admin d ON a.reviewer = d.id
	) s";
        }
        return $sql;
    }

    public function add( array $in )
    {
        $id = $in['id'];
        if ($in['ad_cost'] == '') {
            $this->set_error(' 请输入广告费用！');
            return false;
        }
        //根据id查出已有的运营数据
        $operate = $this->db->query ( "SELECT * FROM operate where id = $id" )->row_array ();
        //上传广告费后,计算相应数据
        $ROI = bcdiv($operate['turnover'],$in['ad_cost'],2);//ROI=营业额/广告费
        $ROI = empty($ROI) ? '0' : $ROI;
        //每单广告成本=广告费/付款订单数
        $unit_ad_cost = bcdiv($in['ad_cost'],$operate['paid_orders'],2);
        $unit_ad_cost = empty($unit_ad_cost) ? '0' : $unit_ad_cost;
        //产品总成本转换为美元
        $product_total_cost_usd = bcdiv($operate['product_total_cost'],$operate['exchange_rate'],2);
        $product_total_cost_usd = empty($product_total_cost_usd) ? '0' : $product_total_cost_usd;
        //毛利=营业额-广告费-手续费-产品总成本(美元)
        $gross_profit = $operate['turnover']-$in['ad_cost']-$operate['formalities_cost']-$product_total_cost_usd;
        //毛利(人民币)
        $gross_profit_rmb = bcmul($gross_profit,$operate['exchange_rate'],2);
        $gross_profit_rmb = empty($gross_profit_rmb) ? '0' : $gross_profit_rmb;
        //毛利率=毛利/营业额
        $gross_profit_rate = bcdiv($gross_profit,$operate['turnover'],9);
        $gross_profit_rate = empty($gross_profit_rate) ? '0' : $gross_profit_rate;
        $in['review_status'] ? $in['review_status'] = $in['review_status'] : $in['review_status'] = 1;
        //写入数据库的数据
        $data = array(
            'ad_cost' => $in['ad_cost'],
            'review_status' => $in['review_status'],
            'review_time' => $in['review_time'],
            'reviewer' => $in['reviewer'],
            'ROI' => $ROI,
            'unit_ad_cost' => $unit_ad_cost,
            'gross_profit' => $gross_profit,
            'gross_profit_rmb' => $gross_profit_rmb,
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

    public function get_users($admin_id)
    {
        if($admin_id == 1){
            $sql = "select id as s_u_id,real_name as s_real_name,user_name as s_user_name from admin order by s_u_id desc";
        }else{
            $sql = 'select s_u_id,s_real_name,s_user_name from admin_org_temp where u_id = '.$admin_id.' group by s_u_id order by s_u_id desc';
        }
        $users = $this->db->query ( $sql )->result_array ();
        return $users;
    }
    
    public function get_sum( $sql, $condition )
    {
        $sql_sum = $sql.$condition[0];
        $data = $this->db->query ( $sql_sum )->result_array ();
        $sum = [];
        foreach($data as $v){
            $sum['turnover'] += $v['turnover'];
            $sum['paid_orders'] += $v['paid_orders'];
            $sum['ad_cost'] += $v['ad_cost'];
            $sum['formalities_cost'] += $v['formalities_cost'];
            $sum['product_total_cost'] += $v['product_total_cost'];
            $sum['gross_profit'] += $v['gross_profit'];
            $sum['gross_profit_rmb'] += $v['gross_profit_rmb'];
        }
        //总营业额
        $sum['turnover'] = floor($sum['turnover'] * 100) / 100;
        //总广告费
        $sum['ad_cost'] = floor($sum['ad_cost'] * 100) / 100;
        //总手续费
        $sum['formalities_cost'] = floor($sum['formalities_cost'] * 100) / 100;
        //总产品总成本
        $sum['product_total_cost'] = floor($sum['product_total_cost'] * 100) / 100;
        //总毛利（$）
        $sum['gross_profit'] = floor($sum['gross_profit'] * 100) / 100;
        //总毛利（￥）
        $sum['gross_profit_rmb'] = floor($sum['gross_profit_rmb'] * 100) / 100;
        //总毛利率 = 总毛利/总营业额
        $sum['gross_profit_rate'] = bcdiv($sum['gross_profit'],$sum['turnover'],9);
        $sum['gross_profit_rate'] = empty($sum['gross_profit_rate']) ? '0.000000000' : $sum['gross_profit_rate'];
        //总ROI = 总营业额/总广告费
        $sum['ROI'] = bcdiv($sum['turnover'],$sum['ad_cost'],2);
        $sum['ROI'] = empty($sum['ROI']) ? '0.00' : $sum['ROI'];
        return $sum;

}

    public function get_product_list( $id )
    {
        //根据id查出已有的运营数据
        $operate = $this->db->query ( "SELECT * FROM operate WHERE id = $id" )->row_array ();
        $shop_id = $operate['shop_id'];
        $date = $operate['datetime'];
        //获取该店铺该天的付款订单id (状态为已支付)
        $sql = "SELECT GROUP_CONCAT(shopify_o_id) AS orders FROM `order` WHERE  shop_id = $shop_id AND datetime = '$date' AND financial_status = 'paid'";
        $orders = $this->db->query ( $sql )->row_array ()['orders'];
        if(!empty($orders)){
            //获取该店铺该天已付订单的所有商品(商品名称,sku编码,出售总数,产品重量,产品价格)
            $sql = "SELECT a.sku_id,sum(a.quantity) as quantity,b.weight,b.price,c.name FROM order_goods a LEFT JOIN goods_sku b ON a.sku_id = b.code LEFT JOIN goods c ON b.spu_id = c.id WHERE shop_id = $shop_id AND shopify_o_id IN ( $orders ) GROUP BY sku_id";
            $data = $this->db->query ( $sql )->result_array ();
            //获取产品成本明细
            $a = 1;
            foreach($data as $k => $v){
                $v['id'] = $a;
                foreach ($v as $key => $val){
                    $data[$k]['id'] = $v['id'];
                    $data[$k]['freight'] = $operate['freight'];
                    $data[$k]['product_cost'] = bcadd(($v['quantity']*$v['price']),($v['quantity']*$v['weight']*$data[$k]['freight']),2);
                }
                $a ++;
            }
        }else{
            $data = array();
        }
        return $data;
    }
}