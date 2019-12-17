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
	a.*, b.real_name,
	c.domain,
	d.real_name AS reviewer_name
FROM
	operate a
LEFT JOIN admin b ON a.user_id = b.id
LEFT JOIN shop c ON a.shop_id = c.id
LEFT JOIN admin d ON a.reviewer = d.id";
        }
        else{
            $sql = "SELECT
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
LEFT JOIN admin d ON a.reviewer = d.id";
        }
        return $sql;
    }

    public function add( array $in )
    {
        $id = $in['id'];
        $data = array(
            'ad_cost' => $in['ad_cost'],
            'review_status' => $in['review_status'],
            'review_time' => $in['review_time'],
            'reviewer' => $in['reviewer']
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
}