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
			a.*, b.real_name,
			c.domain,
			d.real_name AS reviewer_name
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
		LEFT JOIN admin d ON a.reviewer = d.id
	) s";
        }
        return $sql;
    }

    public function add( array $in )
    {
        $id = $in['id'];
        //根据id查出已有的运营数据
        $operate = $this->db->query ( "SELECT * FROM operate where id = $id" )->row_array ();
        //根据user_id获取org_id,获取对应的汇率
        $user_id = $operate['user_id'];
        $org_id = $this->db->query ( "select org_id from admin where id = $user_id" )->row_array ()['org_id'];
        $sql = "select exchange_rate from royalty_rules where o_id in ($org_id) and type = 1";
        $fees = $this->db->query ( $sql )->row_array ();
        $exchange_rate = $fees['exchange_rate'];//汇率
        if(empty($in['ad_cost'])){//广告费未上传时,数据为空
            $ROI = '';
            $unit_ad_cost = '';
            $gross_profit = '';
            $gross_profit_rate = '';
            $in['review_status'] = 0;
        }else{//上传广告费后,计算相应数据
            $ROI = bcdiv($operate['turnover'],$in['ad_cost'],2);//ROI=营业额/广告费
            $unit_ad_cost = bcdiv($in['ad_cost'],$operate['paid_orders'],2);//每单广告成本=广告费/付款订单数
            $unit_ad_cost = empty($unit_ad_cost) ? '0' : $unit_ad_cost;
            $gross_profit = $operate['turnover']-$in['ad_cost']-$operate['formalities_cost']-bcdiv($operate['product_total_cost'],$exchange_rate,2);//毛利=营业额-广告费-手续费-产品总成本/汇率
            $gross_profit_rate = bcdiv($gross_profit,$operate['turnover'],9);//毛利率=毛利/营业额
            $gross_profit_rate = empty($gross_profit_rate) ? '0' : $gross_profit_rate;
            $in['review_status'] ? $in['review_status'] = $in['review_status'] : $in['review_status'] = 1;
        }

        $data = array(
            'ad_cost' => $in['ad_cost'],
            'review_status' => $in['review_status'],
            'review_time' => $in['review_time'],
            'reviewer' => $in['reviewer'],
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