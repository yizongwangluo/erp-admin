<?php


class shopify_orders extends \Application\Component\Common\IFacade
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/shop_data' );
    }


    public function index(){

        $shop_list = $this->shop_data->lists();

        foreach($shop_list as $k=>$value){

        }
    }

}