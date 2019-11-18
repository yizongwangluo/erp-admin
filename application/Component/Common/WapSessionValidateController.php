<?php
/**
 * 移动端会话验证控制器
 * User: xiongbaoshan
 * Date: 2016/7/25
 * Time: 17:03
 */

namespace Application\Component\Common;


class WapSessionValidateController extends WapBaseController
{

    function __construct()
    {
        parent::__construct();
        $this->session_validate();
    }

    protected function session_validate(){

        if(!$this->user){
            redirect($this->router->create_url('wap/user/login'));
        }

    }


}