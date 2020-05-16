<?php

class Goods_sku_facade extends \Application\Component\Common\IFacade {

    function __construct() {
        parent::__construct();
        $this->load->model('data/goods_sku_data');
        $this->load->model('data/goods_sku_apply_data');
    }

}