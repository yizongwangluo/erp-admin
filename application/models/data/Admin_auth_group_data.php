<?php
/**
 * 权限组
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 15:02
 */
class Admin_auth_group_data extends \Application\Component\Common\IData{

    public function get_group_name($ids){
        $sql = 'select GROUP_CONCAT(title) as title from admin_auth_group where id in ('.$ids.')';
        $query = $this->db->query($sql)->row_array();
        return $query['title'];
    }

}