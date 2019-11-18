<?php

/**
 * 后台系统登陆
 * User: xiongbaoshan
 * Date: 2016/7/13
 * Time: 9:46
 */
class Login extends MY_Controller
{

    /**
     * 登陆
     */
    public function index(){
        $this->load->model('facade/admin_facade');

        if($this->admin_facade->get_login_user()){
            redirect('admin');
        }
        $this->load->view();
    }

    /**
     * 验证
     */
    public function check(){
        $this->load->model('facade/admin_facade');
        $user_name=$this->input->post('user_name');
        $user_password=$this->input->post('user_password');

        if(!$this->admin_facade->login($user_name,$user_password)){
            $this->output->ajax_return(AJAX_RETURN_FAIL,$this->admin_facade->get_error());
        }
        $this->output->ajax_return(AJAX_RETURN_SUCCESS,$this->admin_facade->get_error());
    }

    /**
     * 退出
     */
    public function logout(){
        $this->load->model('facade/admin_facade');

        $this->admin_facade->logout();
        redirect('admin/login');
    }


}