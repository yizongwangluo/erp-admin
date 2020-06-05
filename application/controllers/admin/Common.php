<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/6
 * Time: 11:43
 */

class Common  extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('data/admin_organization_data');
        $this->load->model('data/admin_user_org_data');
        $this->load->model('data/admin_data');
    }


    /**
     * 查询运营职位列表
     */
    public function getOrgOperate(){

        $o_id = $this->input->post('o_id');

        $list = $this->admin_organization_data->lists(['is_operate'=>1]);
        $list = array3level($list);
        $option = '';
        foreach($list as $v){
            $option.='<option value="'.$v['id'].'" ';
            if($o_id==$v['id']){
                $option.=' selected ';
            }
            $option.=' >|';
            for ($i=1;$i < $v['level'];$i++){
                $option.= ' ----';
            }
            $option.=$v['name'];
            $option.='</option>';
        }

        $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'OK！',$option );
    }

    /**
     * 查询运营用户列表
     */
    public function getOrgUser(){

        $input= $this->input->post();

        if($input['o_id']){
            $list = $this->admin_user_org_data->getOrgUser($input['o_id']);
        }else{ //所有用户
            $list = $this->admin_data->lists();
        }
        $option = '';
        foreach($list as $v){
            $option.='<option value="'.$v['id'].'" ';
            if($input['u_id']==$v['id']){
                $option.=' selected ';
            }
            $option.='>';
            $option.=$v['user_name'];
            $option.='</option>';
        }
        $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'OK！',$option );
    }
}