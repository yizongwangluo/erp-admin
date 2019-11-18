<?php
/**
 * 移动端基础控制器
 * User: xiongbaoshan
 * Date: 2016/7/25
 * Time: 17:03
 */

namespace Application\Component\Common;


class WapBaseController extends \MY_Controller
{
    /**
     * 当前登陆用户
     * @var null
     */
    protected $user=null;

    function __construct()
    {
        parent::__construct();
        $this->load->model('facade/user_facade');


        $this->init_user();
        $this->common_vars();

    }

    private function init_user(){
        $this->user=$this->user_facade->get_login_user();
        $this->load->vars('user',$this->user);
    }


    private function common_vars(){

    }


}