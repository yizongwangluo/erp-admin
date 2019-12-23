<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/11/29
 * Time: 14:07
 */

class Shop extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/shop_data' );
        $this->load->model ( 'data/apply_data' );
        $this->load->model ( 'data/order_synchro_data' );
    }

    //店铺列表
    public function index($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->shop_data->index($admin_id);
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
        $result = $this->shop_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //店铺查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where domain like '%{$search}%' or company_name like '%{$search}%' or shop_remark like '%{$search}%' or real_name like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    //新增店铺
    public function add ()
    {
        if ( IS_POST ) {
            $input = input ( 'post.' );
            $id = $this->shop_data->add ( $input );

            if ( !$id ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->shop_data->get_error () );
            }

            //添加shopify同步订单记录
//            $this->order_synchro_data->edit_order_synchro($id,$input);

            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $admin_id = $this->admin['id'];
            $data['users'] = $this->shop_data->get_users ($admin_id);
            $data['company'] = $this->shop_data->get_company ($admin_id);
            $this->load->view ( '' ,$data);
        }

    }

    //编辑店铺
    public function edit( $id = null )
    {
        $admin_id = $this->admin['id'];
        $data['info'] = $this->shop_data->get_info ( $id );
        $data['users'] = $this->shop_data->get_users ( $admin_id );
        $data['company'] = $this->shop_data->get_company ( $admin_id );
        $this->load->view ( '@/add', $data );
    }

    //删除店铺
    public function del()
    {
        $id = (int)$this->input->post ( 'id' );
        if (!$this->shop_data->delete($id)) {
            $this->output->ajax_return(AJAX_RETURN_FAIL, '删除失败');
        }
        $this->output->ajax_return(AJAX_RETURN_SUCCESS, '操作成功');
    }

    //申请列表
    public function lists($title = 'date',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->apply_data->index($admin_id,$account_type = '0');
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->query_lists ($this->input->get ());
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->apply_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //待审批
    public function unreviewed($title = 'date',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->apply_data->unreviewed($admin_id,$account_type = '0');
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->query_lists ($this->input->get ());
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->apply_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //已驳回
    public function rejected($title = 'date',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->apply_data->rejected($admin_id,$account_type = '0');
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->query_lists ($this->input->get ());
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->apply_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //已完成
    public function reviewed($title = 'date',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->apply_data->reviewed($admin_id,$account_type = '0');
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->query_lists ($this->input->get ());
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->apply_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    public function review_add()
    {
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->apply_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->apply_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $this->load->view ( '' );
        }
    }

    //审批
    public function review_edit( $id = null )
    {
        $data['url'] = $this->input->get ( 'url' );
        $data['info'] = $this->apply_data->get_info ( $id );
        $this->load->view ( '@/review_add', $data );
    }

    //审批查询条件
    private function query_lists ($input)
    {
        $start_time = $input['start_time'];
        $end_time = $input['end_time'];
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        if($start_time != '' && $end_time != ''){
            //结束时间与开始时间的时间差
            $time_stamp_diff = $end_time - $start_time;
            if($time_stamp_diff < 0)
                $this->output->alert('对不起,查询开始时间必须小于结束时间!');
        }
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){

            if (!empty($start_time) && !empty($end_time)){
                $condition[] = " where (real_name like '%{$search}%' or apply_remark like '%{$search}%' or apply_summary like '%{$search}%') and date >= ".$start_time." and date <= ".$end_time;
            }elseif (!empty($start_time) && empty($end_time)){
                $condition[] = " where (real_name like '%{$search}%' or apply_remark like '%{$search}%' or apply_summary like '%{$search}%') and date >= ".$start_time;
            }elseif (empty($start_time) && !empty($end_time)){
                $condition[] = " where (real_name like '%{$search}%' or apply_remark like '%{$search}%' or apply_summary like '%{$search}%') and date <= ".$end_time;
            }else{
                $condition[] = " where real_name like '%{$search}%' or apply_remark like '%{$search}%' or apply_summary like '%{$search}%'";
            }


        }else{
            if (!empty($start_time) && !empty($end_time)) {
                $condition[] = " where date >= ".$start_time." and date <= ".$end_time;
            }elseif (!empty($start_time) && empty($end_time)){
                $condition[] = " where date >= ".$start_time;
            }elseif (empty($start_time) && !empty($end_time)){
                $condition[] = " where date <= ".$end_time;
            }
        }


        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }
}