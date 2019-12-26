<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/9
 * Time: 11:59
 */

class Datareview_data extends \Application\Component\Common\IData
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
			c.domain
		FROM
			operate a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN shop c ON a.shop_id = c.id
		WHERE
			review_status <> 0
	) s";
        }
        else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain
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
		WHERE
			review_status <> 0
	) s";
        }
        return $sql;
    }

    public function unreviewed( $admin_id ){
        if($admin_id == 1){
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain
		FROM
			operate a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN shop c ON a.shop_id = c.id
		WHERE
			review_status = 1
	) s";
        }
        else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain
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
		WHERE
			review_status = 1
	) s";
        }
        return $sql;
    }

    public function reviewed( $admin_id ){
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
		WHERE
			review_status = 2
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
		WHERE
			review_status = 2
	) s";
        }
        return $sql;
    }
}