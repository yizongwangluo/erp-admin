<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/16
 * Time: 14:16
 */

class My_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
    }

    public function get_user($user_id)
    {
        if(is_numeric($user_id)){
            $sql = "select user_name,real_name from admin where id = $user_id";
            $user = $this->db->query ( $sql )->row_array ();
        }else{
            $user = [];
        }
        return $user;
    }

    public function get_company($company_id)
    {
        if(is_numeric($company_id)) {
            $sql = "select company_name from company where id = $company_id";
            $company = $this->db->query($sql)->row_array();
        }else{
            $company = [];
        }
        return $company;
    }

    public function get_domain($shop_id)
    {
        if(is_numeric($shop_id)) {
            $sql = "select domain from shop where id = $shop_id";
            $domain = $this->db->query($sql)->row_array();
        }else{
            $domain = [];
        }
        return $domain;
    }

    public function get_shops()
    {
        $sql = "select id,domain,status from shop order by status desc";
        $shops = $this->db->query($sql)->result_array();
        return $shops;
    }
}