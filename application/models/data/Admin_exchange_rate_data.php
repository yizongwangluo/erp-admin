<?php
/**
 * Created by PhpStorm.
 * User: liuxiaojie
 * Date: 2020/08/27
 * Time: 12:00
 */

class Admin_exchange_rate_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();

    }

    public function addAll($data = []){

        return $this->db->replace_batch('admin_exchange_rate', $data,true);
//        return $this->db->insert_batch('admin_exchange_rate', $data,true);

    }

}