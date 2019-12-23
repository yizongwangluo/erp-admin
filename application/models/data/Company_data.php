<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/11/19
 * Time: 9:59
 */

class Company_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();

    }

    public function index( $admin_id ){
        if($admin_id == 1){
            $sql = "SELECT
	id,
	company_name,
	agent,
	account_status,
	logout_time,
	company_remark,
	user_name,
	real_name,
	account_num,
	unlimit_num,
	domain
FROM
	(
		SELECT
			k.id,
			k.company_name,
			k.agent,
			k.account_status,
			k.logout_time,
			k.company_remark,
			k.user_name,
			k.real_name,
			k.account_num,
			k.unlimit_num,
			group_concat(e.domain) AS domain
		FROM
			(
				SELECT
					f.id,
					f.company_name,
					f.agent,
					f.account_status,
					f.logout_time,
					f.company_remark,
					f.user_name,
					f.real_name,
					f.account_num,
					count(d.company_account_id) AS unlimit_num
				FROM
					(
						SELECT
							a.id,
							a.company_name,
							a.agent,
							a.account_status,
							a.logout_time,
							a.company_remark,
							b.user_name,
							b.real_name,
							count(c.company_account_id) AS account_num
						FROM
							company a
						LEFT JOIN `admin` b ON b.id = a.belong_to
						LEFT JOIN companyaccount c ON c.company_id = a.id
						GROUP BY
							a.id
					) f
				LEFT JOIN (
					SELECT
						*
					FROM
						companyaccount
					WHERE
						isunlock = '是'
				) d ON d.company_id = f.id
				GROUP BY
					f.id
			) k
		LEFT JOIN shop e ON e.company_id = k.id
		GROUP BY
			k.id
	) s";
        }
        else{
        $sql = 'SELECT
	id,
	company_name,
	agent,
	account_status,
	logout_time,
	company_remark,
	user_name,
	real_name,
	account_num,
	unlimit_num,
	domain
FROM
	(
		SELECT
			k.id,
			k.company_name,
			k.agent,
			k.account_status,
			k.logout_time,
			k.company_remark,
			k.user_name,
			k.real_name,
			k.account_num,
			k.unlimit_num,
			group_concat(e.domain) AS domain
		FROM
			(
				SELECT
					f.id,
					f.company_name,
					f.agent,
					f.account_status,
					f.logout_time,
					f.company_remark,
					f.user_name,
					f.real_name,
					f.account_num,
					count(d.company_account_id) AS unlimit_num
				FROM
					(
						SELECT
							a.id,
							a.company_name,
							a.agent,
							a.account_status,
							a.logout_time,
							a.company_remark,
							b.s_user_name AS user_name,
							b.s_real_name AS real_name,
							count(c.company_account_id) AS account_num
						FROM
							company a
						INNER JOIN (
							SELECT
								s_u_id,
								s_real_name,
								s_user_name
							FROM
								admin_org_temp
							WHERE
								u_id = '.$admin_id.'
							GROUP BY
		                        s_u_id
						) b ON b.s_u_id = a.belong_to
						LEFT JOIN companyaccount c ON c.company_id = a.id
						GROUP BY
							a.id
					) f
				LEFT JOIN (
					SELECT
						*
					FROM
						companyaccount
					WHERE
						isunlock = \'是\'
				) d ON d.company_id = f.id
				GROUP BY
					f.id
			) k
		LEFT JOIN shop e ON e.company_id = k.id
		GROUP BY
			k.id
	) s';
        }
        return $sql;
    }

    public function add( array $in )
    {
        if (empty($in['agent'])) {
            $this->set_error(' 请输入代理商！');
            return false;
        }
        if (empty($in['company_name'])) {
            $this->set_error(' 请输入公司名称！');
            return false;
        }
        if (empty($in['business_license_image'])) {
            $this->set_error(' 请上传营业执照图片！');
            return false;
        }
//        if (empty($in['ad_connect_name'])) {
//            $this->set_error(' 请输入广告主联系人姓名！');
//            return false;
//        }
//        if (empty($in['ad_connect_email'])) {
//            $this->set_error(' 请输入广告主联系人邮箱！');
//            return false;
//        }
        if (empty($in['logout_time'])) {
            $this->set_error(' 请选择下户时间！');
            return false;
        }
        if (!is_numeric($in['account_status'])) {
            $this->set_error(' 请选择开户状态！');
            return false;
        }
        if (!is_numeric($in['belong_to'])) {
            $this->set_error(' 请选择所属人！');
            return false;
        }

        $id = $in['id'];
        $data = array(
            'agent' => $in['agent'],
            'company_name' => $in['company_name'],
            'business_license_image' => $in['business_license_image'],
            'ad_connect_name' => $in['ad_connect_name'],
            'ad_connect_email' => $in['ad_connect_email'],
            'fanslink' => $in['fanslink'],
            'time_zone' => $in['time_zone'],
            'BM' => $in['BM'],
            'account_status' => $in['account_status'],
            'logout_time' => strtotime($in['logout_time']),
            'BMAPI' => $in['BMAPI'],
            'belong_to' => $in['belong_to'],
            'company_remark' => $in['company_remark']
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
            if (!$this->company_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

    public function get_users($admin_id){
        if($admin_id == 1){
            $sql = "select id as s_u_id,real_name as s_real_name,user_name as s_user_name from admin order by s_u_id desc";
        }else{
            $sql = 'select s_u_id,s_real_name,s_user_name from admin_org_temp where u_id = '.$admin_id.' group by s_u_id order by s_u_id desc';
        }
        $users = $this->db->query ( $sql )->result_array ();
        return $users;
    }

    public function get_lists($id)
    {
        $sql = "SELECT
	a.*, b.domain,
	c.user_name,
	c.real_name
FROM
	companyaccount a
LEFT JOIN shop b ON a.shop_id = b.id
LEFT JOIN admin c ON a.user_id = c.id
WHERE
	a.company_id = $id";
        return $sql;
    }


}