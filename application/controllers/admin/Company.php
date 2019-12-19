<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/11/19
 * Time: 9:41
 */

class Company extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/company_data' );
        $this->load->model ( 'data/my_data' );
    }

    //企业主体列表
    public function index($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->company_data->index($admin_id);
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->parse_query_lists ($this->input->get ());
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->company_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where agent like '%{$search}%' or company_name like '%{$search}%' or company_remark like '%{$search}%' or domain like '%{$search}%' or real_name like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    //新增企业主体
    public function add(){
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->company_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->company_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $admin_id = $this->admin['id'];
            $data['users'] = $this->company_data->get_users ($admin_id);
            $this->load->view ( '' ,$data);
        }

    }

    //编辑企业主体
    public function edit( $id = null ,$title = 'id',$sort = 'desc')
    {
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->company_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->company_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $sql = $this->company_data->get_lists($id);
            $page = max ( 1, $this->input->get ( 'page' ) );
            $condition = [];
            $order_t = $this->input->get ( 'title' );
            $order_s = $this->input->get ( 'sort' );
            if(isset($order_t)){
                $title = $order_t;
            }
            if(isset($order_s)){
                $sort = $order_s;
            }
            $data = $this->company_data->list_page ( $sql, $condition, [$title, $sort], $page, 5 );
            $data['page_html'] = create_page_html ( '?', $data['total'],5 );
            $data['info'] = $this->company_data->get_info ( $id );
            $admin_id = $this->admin['id'];
            $data['users'] = $this->company_data->get_users ($admin_id);
            $this->load->view ( '' ,$data);
        }
    }

    //删除企业主体
    public function del()
    {
        $id = (int)$this->input->post ( 'id' );
        if (!$this->company_data->delete($id)) {
            $this->output->ajax_return(AJAX_RETURN_FAIL, '删除失败');
        }
        $this->output->ajax_return(AJAX_RETURN_SUCCESS, '操作成功');
    }

    //企业主体详情
    public function detail ( $id = null )
    {
        $data['info'] = $this->company_data->get_info ( $id );
        $user_id = $data['info']['belong_to'];
        $data['user'] = $this->my_data->get_user ( $user_id );
        $this->load->view ( '', $data );
    }

}

