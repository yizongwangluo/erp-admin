<?php

/**
 * 更新缓存
 * User: xiaopeiiep
 * Date: 2017/2/21
 * Time: 17:50
 */
class Cache extends \Application\Component\Common\AdminPermissionValidateController
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 更新缓存
     */
    public function index(){
        $this->load->view();
    }

    public function mobile(){
	    $this->load->view();
    }

    public function redis(){
    	if ($this->cache->clean()){
    		$this->output->success('redis缓存清空完成');
	    }

    }
}