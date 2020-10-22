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
        $this->load->model ( 'data/goods_apply_data' );
    }

    /**
     * 控制台首页
     */
    public function index(){

        if(IS_POST){

            $data = [];

            //广告费充值申请
            $data['advert_sum'] = $this->advert_data->count(['status'=>0]);
            $data['goods_dsh'] =$this->goods_apply_data->count(['status'=>2]);

            $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok',$data);
        }else{

            $data = [];

            //广告费充值申请
            $data['advert_sum'] = $this->advert_data->count(['status'=>0]);
            $data['order_error'] =$this->order_data->count(['shop_id'=>0]);
            $data['goods_dsh'] =$this->goods_apply_data->count(['status'=>2]);

            $this->load->view ( '', $data );
        }
    }

}