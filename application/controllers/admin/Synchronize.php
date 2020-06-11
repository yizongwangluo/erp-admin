<?php
/**
 * Created by PhpStorm.
 * User: akimbo
 * Date: 2020/2/11
 * Time: 18:30
 */

class Synchronize extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        set_time_limit ( 0 );
        $this->load->model ( 'data/my_data' );
        $this->load->model ( 'operate/synchronize_operate' );
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