<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/9
 * Time: 11:38
 */

class Datareview extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/operate_data' );
        $this->load->model ( 'data/datareview_data' );

    }

    //审核数据列表(全部)
    public function index($title = 'datetime',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->datareview_data->index($admin_id);
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
        $result = $this->datareview_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //查询条件
    private function parse_query_lists ($input)
    {
        $start_time = $input['start_time'];
        $end_time = $input['end_time'];
        if($start_time != '' && $end_time != ''){
            //结束时间与开始时间的时间差
            $time_stamp_diff = strtotime($end_time) - strtotime($start_time);
            if($time_stamp_diff < 0)
                $this->output->alert('对不起,查询开始时间必须小于结束时间!');
        }
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){

            if (!empty($start_time) && !empty($end_time)){
                $condition[] = " where (user_name like '%{$search}%' or domain like '%{$search}%') and datetime >= '{$start_time}' and datetime <= '{$end_time}'";
            }elseif (!empty($start_time) && empty($end_time)){
                $condition[] = " where (user_name like '%{$search}%' or domain like '%{$search}%') and datetime >= '{$start_time}'";
            }elseif (empty($start_time) && !empty($end_time)){
                $condition[] = " where (user_name like '%{$search}%' or domain like '%{$search}%') and datetime <= '{$end_time}'";
            }else{
                $condition[] = " where user_name like '%{$search}%' or domain like '%{$search}%'";
            }

        }else{
            if (!empty($start_time) && !empty($end_time)) {
                $condition[] = " where datetime >= '{$start_time}' and datetime <= '{$end_time}'";
            }elseif (!empty($start_time) && empty($end_time)){
                $condition[] = " where datetime >= '{$start_time}'";
            }elseif (empty($start_time) && !empty($end_time)){
                $condition[] = " where datetime <= '{$end_time}'";
            }
        }

        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    //待审核数据列表
    public function unreviewed($title = 'datetime',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->datareview_data->unreviewed($admin_id);
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->parse_query_lists ($this->input->get ());
//        echo $condition[0]."<br>";
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->datareview_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //已审核数据列表
    public function reviewed($title = 'datetime',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->datareview_data->reviewed($admin_id);
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->parse_query_lists ($this->input->get ());
//        echo $condition[0]."<br>";
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->datareview_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    public function add()
    {
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->operate_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->operate_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $this->load->view ( '' );
        }
    }

    //审核
    public function edit( $id = null )
    {
        $data['url'] = $this->input->get ( 'url' );
        $data['info'] = $this->operate_data->get_info ( $id );
        $this->load->view ( '@/add', $data );
    }
}