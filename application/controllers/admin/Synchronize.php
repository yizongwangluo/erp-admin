<?php
/**
 * Created by PhpStorm.
 * User: akimbo
 * Date: 2020/2/11
 * Time: 18:30
 */

set_time_limit ( 0 );

class Synchronize extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/my_data' );
        $this->load->model ( 'operate/synchronize_operate' );
        $this->load->model ( 'orders/mabang_orders' );

    }

    public function index()
    {
        $result['shops'] = $this->my_data->get_shops();
        $this->load->view('',$result);
    }

    //同步订单
    public function synchronize_save ()
    {
        if ( IS_POST ) {
            $input = $this->input->post();
//            $input = $this->input->get();

            if ( !$this->synchronize_operate->sync ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->synchronize_operate->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        }
    }

    //同步订单 - 马帮
    public function synchronize_save_mb ()
    {
        if ( IS_POST ) {
//        if ( IS_GET ) {

            $input = $this->input->post();
//            $input = $this->input->get();

            $start_time = $input['start_time'];
            $end_time = $input['end_time'];

            if(!$start_time){
                $this->output->ajax_return ( AJAX_RETURN_FAIL, '请输入开始时间' );
            }
            if(!$end_time){
                $this->output->ajax_return ( AJAX_RETURN_FAIL, '请输入结束时间！' );
            }
            if($start_time>$end_time){
                $this->output->ajax_return ( AJAX_RETURN_FAIL, '开始时间必须小于或等于结束时间！' );
            }

            if ( !$this->mabang_orders->get_order_in_time ( $start_time,$end_time ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->mabang_orders->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        }
    }

    public function up_operate()
    {
        if ( IS_POST ) {
            $input = $this->input->post();

            if ( !$this->synchronize_operate->up_operate ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->synchronize_operate->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        }
    }

    /**
     * 修复订单时间
     */
    public function repair_order_time(){

        if ( IS_POST ) {
            $input = $this->input->post();

            if ( !$this->synchronize_operate->repair_order_time ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->synchronize_operate->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        }

    }


}