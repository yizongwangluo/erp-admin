<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/5
 * Time: 14:26
 */

class Apply_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();

    }

    public function index( $admin_id,$account_type ){
        if($admin_id == 1){
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.user_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.user_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			account_type = $account_type
	) s";
            }
        }
        else{
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_user_name AS user_name,
			c.user_name AS reviewer_name
		FROM
			apply a
		INNER JOIN (
			SELECT
				s_u_id,
				s_user_name
			FROM
				admin_org_temp
			WHERE
				u_id = $admin_id
			GROUP BY
				s_u_id
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_user_name AS user_name,
			c.user_name AS reviewer_name
		FROM
			apply a
		INNER JOIN (
			SELECT
				s_u_id,
				s_user_name
			FROM
				admin_org_temp
			WHERE
				u_id = $admin_id
			GROUP BY
				s_u_id
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			account_type = $account_type
	) s";
            }

        }
        return $sql;
    }

    public function unreviewed( $admin_id,$account_type ){
        if($admin_id == 1){
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 0
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 0
		AND account_type = $account_type
	) s";
            }
        }
        else{
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_real_name AS real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
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
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 0
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_real_name AS real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
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
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 0
		AND account_type = $account_type
	) s";
            }

        }
        return $sql;
    }

    public function rejected( $admin_id,$account_type ){
        if($admin_id == 1){
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 1
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 1
		AND account_type = $account_type
	) s";
            }

        }
        else{
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_real_name AS real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
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
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 1
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_real_name AS real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
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
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 1
		AND account_type = $account_type
	) s";
            }

        }
        return $sql;
    }

    public function reviewed( $admin_id,$account_type ){
        if($admin_id == 1){
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 2
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 2
		AND account_type = $account_type
	) s";
            }

        }
        else{
            if($account_type == 'all'){
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_real_name AS real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
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
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 2
	) s";
            }else{
                $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.s_real_name AS real_name,
			c.real_name AS reviewer_name
		FROM
			apply a
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
		) b ON a.user_id = b.s_u_id
		LEFT JOIN admin c ON a.reviewer = c.id
		WHERE
			apply_status = 2
		AND account_type = $account_type
	) s";
            }

        }
        return $sql;
    }

    public function add( array $in )
    {
        if (!is_numeric($in['account_type'])) {
            $this->set_error(' 请选择账号类型！');
            return false;
        }
        if (empty($in['apply_summary'])) {
            $this->set_error(' 请输入申请概要！');
            return false;
        }
        $id = $in['id'];
        $data = array(
            'user_id' => $in['user_id'],
            'date' => $in['date'],
            'type' => $in['type'],
            'account_type' => $in['account_type'],
            'apply_summary' => $in['apply_summary'],
            'apply_remark' => $in['apply_remark'],
            'reviewer' => $in['reviewer'],
            'review_time' => $in['review_time'],
            'apply_status' => $in['apply_status'],
            'annotate' => $in['annotate']
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
            if (!$this->apply_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

}