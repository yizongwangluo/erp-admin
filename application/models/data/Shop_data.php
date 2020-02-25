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

    /**
     * 判断是否已存在该数据
     * @param array $input
     * @return bool
     */
    public function removal($input = array()){

        $data = [];

        $data['id !=']      = $input['id'] ? $input['id']:'';
        $data['domain']    	 = $input['domain'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
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
	shop_api_key,
	shop_api_pwd,
	authorization_erp,
	shop_remark,
	company_name,
	company_domain,
	real_name,
	user_name,
	code,
	shop_package
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
			c.shop_api_key,
	        c.shop_api_pwd,
			c.authorization_erp,
			c.shop_remark,
			c.company_name,
			c.company_domain,
			c.code,
			c.shop_package,
			d.real_name,
			d.user_name 
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
					a.shop_api_key,
	                a.shop_api_pwd,
					a.authorization_erp,
					a.shop_remark,
					a.user_id,
					a.code,
					a.shop_package,
					b.company_name,
					b.domain as company_domain
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
	shop_api_key,
	shop_api_pwd,
	authorization_erp,
	shop_remark,
	company_name,
	company_domain,
	real_name,
	user_name,
	code,
	shop_package
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
			c.shop_api_key,
	        c.shop_api_pwd,
			c.authorization_erp,
			c.shop_remark,
			c.company_name,
			c.company_domain,
			c.code,
			c.shop_package,
			d.s_real_name AS real_name,
			d.s_user_name AS user_name
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
					a.shop_api_key,
	                a.shop_api_pwd,
					a.authorization_erp,
					a.shop_remark,
					a.user_id,
					a.code,
					a.shop_package,
					b.company_name,
					b.domain as company_domain
				FROM
					shop a
				LEFT JOIN company b ON a.company_id = b.id
			) c
		INNER JOIN (
			SELECT
				s_u_id,
				s_real_name,
				s_user_name
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
//        if (empty($in['backstage_username'])) {
//            $this->set_error(' 请输入后台用户名！');
//            return false;
//        }
//        if (empty($in['backstage_password'])) {
//            $this->set_error(' 请输入后台密码！');
//            return false;
//        }
//        if (empty($in['customer_service_email'])) {
//            $this->set_error(' 请输入客服邮箱！');
//            return false;
//        }
//        if (empty($in['email_password'])) {
//            $this->set_error(' 请输入邮箱密码！');
//            return false;
//        }
//        if (empty($in['receipt_paypal'])) {
//            $this->set_error(' 请输入收款paypal！');
//            return false;
//        }
//        if (empty($in['receipt_credit_card'])) {
//            $this->set_error(' 请输入收款信用卡通道！');
//            return false;
//        }
//        if (empty($in['deduction'])) {
//            $this->set_error(' 请输入扣款方式！');
//            return false;
//        }

        if (empty($in['shop_api_key'])) {
            $this->set_error(' 请输入店铺API密钥！');
            return false;
        }
        if (empty($in['shop_api_pwd'])) {
            $this->set_error(' 请输入店铺API密码！');
            return false;
        }
        if (empty($in['authorization_erp'])) {
            $this->set_error(' 请选择是否授权ERP！');
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
//        if (empty($in['code'])) {
//            $this->set_error(' 请输入代码！');
//            return false;
//        }

        $id = $in['id'];
        $domain = trim($in['domain']);
        $domain = preg_replace("/^http(s)?:\/\//", '', $domain);
        $backstage = trim($in['backstage']);
        $backstage = preg_replace("/^http(s)?:\/\//", '', $backstage);
        if(substr($backstage,-1) != '/'){
            $backstage = $backstage."/";
        };

        $data = array(
            'domain' => $domain,
            'backstage' => $backstage,
            'backstage_username' => $in['backstage_username'],
            'backstage_password' => $in['backstage_password'],
            'email_password' => $in['email_password'],
            'receipt_paypal' => $in['receipt_paypal'],
            'receipt_credit_card' => $in['receipt_credit_card'],
            'deduction' => $in['deduction'],
            'customer_service_email' => $in['customer_service_email'],
            'shop_api_key' => $in['shop_api_key'],
            'shop_api_pwd' => $in['shop_api_pwd'],
            'authorization_erp' => $in['authorization_erp'],
            'company_id' => $in['company_id'],
            'user_id' => $in['user_id'],
            'shop_remark' => $in['shop_remark'],
            'code' => $in['code'],
            'shop_package' => $in['shop_package']
        );

        function  filtrfunction($arr){
            if($arr === '' || $arr === null){
                return false;
            }
            return true;
        }

        if (!$id) {
            if($this->removal($data)){
                $this->set_error(' 该店铺已存在，无法重复添加！');
                return false;
            }
            $data = array_filter($data,'filtrfunction');
            $id = $this->store($data);
            if (!$id) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
            return $id;
        }else{
            $data['id'] = $id;
            if($this->removal($data)){
                $this->set_error(' 该店铺已存在！');
                return false;
            }
            unset($data['id']);
            if (!$this->shop_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
            $company_id = $in['company_id'];
            $user_id = $in['user_id'];
            $this->db->query ( "update companyaccount set company_id = $company_id , user_id = $user_id where shop_id = $id " );
            return $id;
        }
    }

    public function get_users($admin_id)
    {
        if($admin_id == 1){
            $sql = "select id as s_u_id,real_name as s_real_name,user_name as s_user_name from admin order by s_u_id desc";
        }else{
            $sql = "select s_u_id,s_real_name,s_user_name from admin_org_temp where u_id = $admin_id group by s_u_id order by s_u_id desc";
        }
        $users = $this->db->query ( $sql )->result_array ();
        return $users;
    }

    public function get_company()
    {
        $sql = "select id,company_name,domain from company order by id desc";
        $company = $this->db->query ( $sql )->result_array ();
        return $company;
    }

}