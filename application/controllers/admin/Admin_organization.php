<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 17:35
 */
class Admin_organization extends \Application\Component\Common\AdminPermissionValidateController{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/admin_organization_data' );
    }

    public function index(){
        $this->load->view ('');
    }

    /**
     * 查询组织列表
     */
    public function lists(){

        $list = $this->admin_organization_data->lists();

        foreach($list as $k=>$v){
            $list[$k]['title'] = $v['name'];
            if( !$v['status']){ $list[$k]['disabled'] = true;  }
            unset($list[$k]['name']);
            unset($list[$k]['level']);
            unset($list[$k]['status']);
        }

        $list = list_to_tree($list,'id','pid','children');

        if(!$list){
            $this->output->ajax_return ( AJAX_RETURN_FAIL, '获取组织结构失败' );
        }else{
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok',['list'=>$list[0]] );
        }
    }


    /**
     * 添加
     */
    public function add(){

        $list = $this->admin_organization_data->lists();

        $newData['list'] = array2level ( $list );

        $this->load->view('',$newData);
    }

    /**
     * 修改
     * @param int $id
     */
    public function edit($id = 0){

        $list = $this->admin_organization_data->lists();//获取组织列表

        $newData['list'] = array2level ( $list ); //把组织列表转换成tree结构

        $this->load->view('@/add',$newData);
    }

    /**
     * 保存
     */
    public function save(){
        $input = $this->input->post();

        if($input['id']){ //修改
            $ret = $this->admin_organization_data->edit($input);
        }else{ //新增
            $ret = $this->admin_organization_data->add($input);
        }
        if(!$ret){
            $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_organization_data->get_error() );
        }else{
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        }
    }

    /**
     * 删除指定组织结构
     * @param int $id
     */
    public function del($id = 0){
        if(!$id){
            $this->output->ajax_return ( AJAX_RETURN_FAIL,'删除失败！' );
        }

        $ret = $this->admin_organization_data->del($id);

        if(!$ret){
            $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_organization_data->get_error() );
        }else{
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, '删除成功！' );
        }
    }

}