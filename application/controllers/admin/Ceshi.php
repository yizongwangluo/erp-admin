<?php

class Ceshi extends \Application\Component\Common\AdminPermissionValidateController {

    function __construct() {
        parent::__construct();
    }

    public function index(){

        $this->erpApi->get_order();

    }

    public function ceshi(){
        echo 12312312;
    }
}