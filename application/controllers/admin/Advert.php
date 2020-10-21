<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/11/29
 * Time: 14:07
 */

class Advert extends \Application\Component\Common\AdminPermissionValidateController
{

    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/advert_data' );
        $this->load->model ( 'data/admin_data' );
    }

    /**
     * 申请列表
     */
    public function index()
    {
        $input = $this->input->get();

        $page = max(1,$input['page']);

        unset($input['page']);

        $result = $this->advert_data->get_list ( $this->admin['id'], $input, $page);

        $userList = $this->admin_data->get_field_by_where(['id','user_name','real_name'],[],true);
        $result['user_list'] = array_column($userList,null,'id');

        $result['page_html'] = create_page_html ( '?', $result['total'] );
        $this->load->view('',$result);
    }


    /**
     * 新增
     */
    public function add(){

        if(IS_POST){

            $input = $this->input->post();

            $input['u_id'] = $this->admin['id'];

            $ret = $this->advert_data->save($input);

            if($ret){ //成功
                $this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
            }else{
                $this->output->ajax_return(AJAX_RETURN_FAIL,$this->advert_data->get_error());
            }

        }else{

            $this->load->view ();
        }
    }

    /**
     * 修改
     * @param int $id
     */
    public function edit($id = 0){

        if(IS_POST){
            $input = $this->input->post();

            $ret = $this->advert_data->save($input);

            if($ret){ //成功
                $this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
            }else{
                $this->output->ajax_return(AJAX_RETURN_FAIL,$this->advert_data->get_error());
            }

        }else{

            $info =  $this->advert_data->get_info($id);

            $this->load->view ('@/add',['info'=>$info]);
        }
    }

    /**
     * 修改
     * @param int $id
     */
    public function examine($id = 0){

        if(IS_POST){
            $input = $this->input->post();

            $input['audit_u_id'] = $this->admin['id'];

            $ret = $this->advert_data->save($input);

            if($ret){ //成功
                $this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
            }else{
                $this->output->ajax_return(AJAX_RETURN_FAIL,$this->advert_data->get_error());
            }

        }else{

            $info =  $this->advert_data->get_info($id);

            $info['user_info']  = $this->admin_data->get_info($info['u_id']);

            $this->load->view ('@/examine',['info'=>$info]);
        }
    }

    /**
     * 删除
     */
    public function del(){

        $id = input('id');

        if($id){
            $ret = $this->advert_data->delete($id);

            if($ret){ //成功
                $this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
            }else{
                $this->output->ajax_return(AJAX_RETURN_FAIL,$this->advert_data->get_error());
            }
        }
    }

}