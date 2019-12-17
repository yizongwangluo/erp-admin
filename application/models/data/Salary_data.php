<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/12
 * Time: 16:38
 */

class Salary_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();

    }

    public function mylist ($admin_id)
    {
        $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			(
				a.basic_salary + a.commission
			) AS total
		FROM
			salary a
		LEFT JOIN admin b ON a.user_id = b.id
		WHERE
			user_id = $admin_id
	) s";
        return $sql;
    }

    public function index($admin_id)
    {
        if($admin_id == 1){
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			(
				a.basic_salary + a.commission
			) AS total
		FROM
			salary a
		LEFT JOIN admin b ON a.user_id = b.id
	) s";
        }else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			(
				a.basic_salary + a.commission
			) AS total
		FROM
			salary a
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
	) s";
        }
        return $sql;
    }

    public function add( array $in )
    {
        $id = $in['id'];
        $data = array(
            'basic_salary' => $in['basic_salary'],
            'salary_remark' => $in['salary_remark'],
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
            if (!$this->salary_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

    public function get_users($admin_id){
        if($admin_id == 1){
            $sql = "select id as s_u_id,real_name as s_real_name from admin order by s_u_id desc";
        }else{
            $sql = 'select s_u_id,s_real_name from admin_org_temp where u_id = '.$admin_id.' group by s_u_id order by s_u_id desc';
        }
        $users = $this->db->query ( $sql )->result_array ();
        return $users;
    }

    public function payroll( array $in)
    {
        $ids = $in['ids'];
        $sql = "UPDATE salary SET salary_status = 1 WHERE id IN ($ids)";
        return $this->db->query($sql);
    }

}