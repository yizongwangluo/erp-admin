<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/3
 * Time: 12:02
 */

class Companyaccount_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
    }

    public function index ($admin_id)
    {
        if($admin_id == 1){
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.id,
			a.company_account_id,
			a.shop_id,
			a.company_id,
			a.isunlock,
			a.status,
			a.companyaccount_remark,
			a.user_id,
			b.real_name,
			c.domain,
			d.company_name
		FROM
			companyaccount a
		LEFT JOIN admin b ON b.id = a.user_id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN company d ON a.company_id = d.id
		GROUP BY
			a.id
	) s";
        }else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.id,
			a.company_account_id,
			a.shop_id,
			a.company_id,
			a.isunlock,
			a.status,
			a.companyaccount_remark,
			a.user_id,
			b.s_real_name AS real_name,
			c.domain,
			d.company_name
		FROM
			companyaccount a
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
		) b ON b.s_u_id = a.user_id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN company d ON a.company_id = d.id
	) s";
        }
        return $sql;
    }

    public function  add ( array $in )
    {
        if (empty($in['company_account_id'])) {
            $this->set_error(' 请输入企业账户ID！');
            return false;
        }
        if (empty($in['shop_id'])) {
            $this->set_error(' 请选择网站域名！');
            return false;
        }
        if (empty($in['company_id'])) {
            $this->set_error(' 请选择所属企业主体！');
            return false;
        }
        if (empty($in['isunlock'])) {
            $this->set_error(' 请选择是否解限！');
            return false;
        }
        if (!is_numeric($in['status'])) {
            $this->set_error(' 请选择账户状态！');
            return false;
        }
        if (empty($in['user_id'])) {
            $this->set_error(' 请选择所属人！');
            return false;
        }

        $id = $in['id'];
        $data = array(
            'company_account_id' => $in['company_account_id'],
            'shop_id' => $in['shop_id'],
            'company_id' => $in['company_id'],
            'isunlock' => $in['isunlock'],
            'status' => $in['status'],
            'user_id' => $in['user_id'],
            'companyaccount_remark' => $in['companyaccount_remark']
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
            if (!$this->companyaccount_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
//            echo $this->db->last_query();exit;
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

    public function get_domain($admin_id)
    {
        if($admin_id == 1){
            $sql = "select id,domain from shop order by id desc";
        }else{
            $sql = "select id,domain from shop a inner join (select s_u_id from admin_org_temp where u_id = $admin_id group by s_u_id) b on a.user_id = b.s_u_id order by id desc";
        }
        $domains = $this->db->query ( $sql )->result_array ();
        return $domains;
    }
}