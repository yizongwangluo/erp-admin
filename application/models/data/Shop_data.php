<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/11/29
 * Time: 15:14
 */

class Shop_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
    }

    public function index ($admin_id)
    {
        if($admin_id == 1){
           $sql = "SELECT
	id,
	domain,
	backstage,
	receipt_paypal,
	receipt_credit_card,
	deduction,
	customer_service_email,
	shop_api,
	authorization_erp,
	shop_remark,
	company_name,
	real_name,
	code
FROM
	(
		SELECT
			c.id,
			c.domain,
			c.backstage,
			c.receipt_paypal,
			c.receipt_credit_card,
			c.deduction,
			c.customer_service_email,
			c.shop_api,
			c.authorization_erp,
			c.shop_remark,
			c.company_name,
			c.code,
			d.real_name 
		FROM
			(
				SELECT
					a.id,
					a.domain,
					a.backstage,
					a.receipt_paypal,
					a.receipt_credit_card,
					a.deduction,
					a.customer_service_email,
					a.shop_api,
					a.authorization_erp,
					a.shop_remark,
					a.user_id,
					a.code,
					b.company_name
				FROM
					shop a
				LEFT JOIN company b ON a.company_id = b.id
			) c
		LEFT JOIN admin d ON c.user_id = d.id
		GROUP BY
			c.id
	) s";
        }
        else{
            $sql = "SELECT
	id,
	domain,
	backstage,
	receipt_paypal,
	receipt_credit_card,
	deduction,
	customer_service_email,
	shop_api,
	authorization_erp,
	shop_remark,
	company_name,
	real_name,
	code
FROM
	(
		SELECT
			c.id,
			c.domain,
			c.backstage,
			c.receipt_paypal,
			c.receipt_credit_card,
			c.deduction,
			c.customer_service_email,
			c.shop_api,
			c.authorization_erp,
			c.shop_remark,
			c.company_name,
			c.code,
			d.s_real_name AS real_name
		FROM
			(
				SELECT
					a.id,
					a.domain,
					a.backstage,
					a.receipt_paypal,
					a.receipt_credit_card,
					a.deduction,
					a.customer_service_email,
					a.shop_api,
					a.authorization_erp,
					a.shop_remark,
					a.user_id,
					a.code,
					b.company_name
				FROM
					shop a
				LEFT JOIN company b ON a.company_id = b.id
			) c
		INNER JOIN (
			SELECT
				s_u_id,
				s_real_name
			FROM
				admin_org_temp
			WHERE
				u_id = $admin_id
			GROUP BY
		        s_u_id
		) d ON c.user_id = d.s_u_id
		GROUP BY
			c.id
	) s";
        }
        return $sql;
    }

    public function  add ( array $in )
    {
        if (empty($in['domain'])) {
            $this->set_error(' 请输入网站域名！');
            return false;
        }
        if (empty($in['backstage'])) {
            $this->set_error(' 请输入网站后台！');
            return false;
        }
        if (empty($in['backstage_username'])) {
            $this->set_error(' 请输入后台用户名！');
            return false;
        }
        if (empty($in['backstage_password'])) {
            $this->set_error(' 请输入后台密码！');
            return false;
        }
        if (empty($in['email_password'])) {
            $this->set_error(' 请输入邮箱密码！');
            return false;
        }
        if (empty($in['receipt_paypal'])) {
            $this->set_error(' 请输入收款paypal！');
            return false;
        }
        if (empty($in['receipt_credit_card'])) {
            $this->set_error(' 请输入收款信用卡通道！');
            return false;
        }
        if (empty($in['deduction'])) {
            $this->set_error(' 请输入扣款方式！');
            return false;
        }
        if (empty($in['customer_service_email'])) {
            $this->set_error(' 请输入客服邮箱！');
            return false;
        }
        if (empty($in['shop_api'])) {
            $this->set_error(' 请输入店铺API！');
            return false;
        }
        if (empty($in['authorization_erp'])) {
            $this->set_error(' 请选择授权ERP！');
            return false;
        }
        if (empty($in['company_id'])) {
            $this->set_error(' 请选择所属企业主体！');
            return false;
        }
        if (empty($in['user_id'])) {
            $this->set_error(' 请选择所属人！');
            return false;
        }
        if (empty($in['code'])) {
            $this->set_error(' 请输入代码！');
            return false;
        }

        $id = $in['id'];
        $data = array(
            'domain' => $in['domain'],
            'backstage' => $in['backstage'],
            'backstage_username' => $in['backstage_username'],
            'backstage_password' => $in['backstage_password'],
            'email_password' => $in['email_password'],
            'receipt_paypal' => $in['receipt_paypal'],
            'receipt_credit_card' => $in['receipt_credit_card'],
            'deduction' => $in['deduction'],
            'customer_service_email' => $in['customer_service_email'],
            'shop_api' => $in['shop_api'],
            'authorization_erp' => $in['authorization_erp'],
            'company_id' => $in['company_id'],
            'user_id' => $in['user_id'],
            'shop_remark' => $in['shop_remark'],
            'code' => $in['code']
        );

        function  filtrfunction($arr){
            if($arr === '' || $arr === null){
                return false;
            }
            return true;
        }

        $data = array_filter($data,'filtrfunction');

        if (!$id) {
            if (!$this->store($data)) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
        }else{
            unset($in['id']);
            if (!$this->shop_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

    public function get_users($admin_id)
    {
        if($admin_id == 1){
            $sql = "select id as s_u_id,real_name as s_real_name from admin order by s_u_id desc";
        }else{
            $sql = "select s_u_id,s_real_name from admin_org_temp where u_id = $admin_id group by s_u_id order by s_u_id desc";
        }
        $users = $this->db->query ( $sql )->result_array ();
        return $users;
    }

    public function get_company($admin_id)
    {
//        $sql = "select id,company_name from company a inner join (select s_u_id from admin_org_temp where u_id = $admin_id group by s_u_id) b on a.belong_to = b.s_u_id order by id desc";
        $sql = "select id,company_name from company order by id desc";
        $company = $this->db->query ( $sql )->result_array ();
        return $company;
    }

}