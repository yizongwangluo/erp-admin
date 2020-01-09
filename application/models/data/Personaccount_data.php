<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/3
 * Time: 18:09
 */

class Personaccount_data extends \Application\Component\Common\IData
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
        $data['person_username']    	 = $input['person_username'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
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
			a.person_username,
			a.person_password,
			a.cookies,
			a.RdoIp,
			a.Rdo_username,
			a.Rdo_password,
			a.Rdo_port,
			a.first_login_time,
			a.type,
			a.company_id,
			a.belongto,
			a.person_status,
			a.person_remark,
			b.real_name,
			b.user_name,
			c.company_name
		FROM
			personaccount a
		LEFT JOIN admin b ON a.belongto = b.id
		LEFT JOIN company c ON a.company_id = c.id
		GROUP BY
			a.id
	) s";
        }
        else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.id,
			a.person_username,
			a.person_password,
			a.cookies,
			a.RdoIp,
			a.Rdo_username,
			a.Rdo_password,
			a.Rdo_port,
			a.first_login_time,
			a.type,
			a.company_id,
			a.belongto,
			a.person_status,
			a.person_remark,
			b.s_real_name AS real_name,
			b.s_user_name AS user_name,
			c.company_name
		FROM
			personaccount a
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
		) b ON a.belongto = b.s_u_id
		LEFT JOIN company c ON a.company_id = c.id
	) s";
        }
        return $sql;
    }

    public function  add ( array $in )
    {
        if (empty($in['person_username'])) {
            $this->set_error(' 请输入用户名！');
            return false;
        }
        if (empty($in['person_password'])) {
            $this->set_error(' 请输入密码！');
            return false;
        }
        if (empty($in['RdoIp'])) {
            $this->set_error(' 请输入RdoIp！');
            return false;
        }
        if (empty($in['Rdo_username'])) {
            $this->set_error(' 请输入Rdo用户名！');
            return false;
        }
        if (empty($in['Rdo_password'])) {
            $this->set_error(' 请输入Rdo密码！');
            return false;
        }
        if (empty($in['Rdo_port'])) {
            $this->set_error(' 请输入Rdo端口！');
            return false;
        }
        if (empty($in['first_login_time'])) {
            $this->set_error(' 请选择首次登陆时间！');
            return false;
        }
        if (!is_numeric($in['type'])) {
            $this->set_error(' 请选择类型！');
            return false;
        }
        if (!is_numeric($in['company_id'])) {
            $this->set_error(' 请选择所属企业主体！');
            return false;
        }
        if (!is_numeric($in['belongto'])) {
            $this->set_error(' 请选择所属人！');
            return false;
        }
        if (!is_numeric($in['person_status'])) {
            $this->set_error(' 请选择状态！');
            return false;
        }
        if (empty($in['cookies'])) {
            $this->set_error(' 请填写cookies！');
            return false;
        }

        $id = $in['id'];
        $data = array(
            'person_username' => trim($in['person_username']),
            'person_password' => $in['person_password'],
            'RdoIp' => $in['RdoIp'],
            'Rdo_username' => $in['Rdo_username'],
            'Rdo_password' => $in['Rdo_password'],
            'Rdo_port' => $in['Rdo_port'],
            'first_login_time' => strtotime($in['first_login_time']),
            'type' => $in['type'],
            'company_id' => $in['company_id'],
            'belongto' => $in['belongto'],
            'person_status' => $in['person_status'],
            'person_remark' => $in['person_remark'],
            'cookies' => $in['cookies']
        );

        function  filtrfunction($arr){
            if($arr === '' || $arr === null){
                return false;
            }
            return true;
        }

        if (!$id) {
            if($this->removal($data)){
                $this->set_error(' 该个人账号已存在，无法重复添加！');
                return false;
            }
            $data = array_filter($data,'filtrfunction');
            if (!$this->store($data)) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
        }else{
            $data['id'] = $id;
            if($this->removal($data)){
                $this->set_error(' 该个人账号已存在！');
                return false;
            }
            unset($data['id']);
            if (!$this->personaccount_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
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