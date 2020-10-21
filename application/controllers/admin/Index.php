<?php
/**
 * Created by PhpStorm.
 * User: liuxiaojie
 * Date: 2020/10/21
 * Time: 14:07
 */

class Index extends \Application\Component\Common\AdminPermissionValidateController
{

    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/advert_data' );
        $this->load->model ( 'data/order_data' );
    }

    public function index(){

        $data = [];

        //广告费充值申请
        $data['advert_sum'] = $this->advert_data->count(['status'=>0]);
        $data['order_error'] =$this->order_data->count(['shop_id'=>0]);

        $this->load->view ( '', $data );
    }
}