<?php
/**
 * 前台会话验证控制器
 * User: xiongbaoshan
 * Date: 2016/7/25
 * Time: 17:03
 */

namespace Application\Component\Common;


class HomeSessionValidateController extends HomeBaseController
{

    public $store_info=null;
    public function __construct()
    {
        parent::__construct();
//        $this->session_validate();
//        $this->load->model('data/store_data');
//        $this->load->model('facade/store_facade');
        //获取当前店铺信息
//        $this->initStoreInfo();
        //放到用户已登陆的全局中
//        $this->load->vars('store_info',$this->store_info);
    }

    protected function session_validate(){

//        if(!$this->user){
//            //手机端跳转到移动版登录页面
//            if(IS_WAP || $_SERVER['HTTP_HOST']{0} == 'm'){
//                redirect($this->router->create_url("mobile/login/index"));
//                exit;
//            }
//            redirect('/');
//        }
    }

}