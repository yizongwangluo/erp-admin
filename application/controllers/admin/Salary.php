<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/12
 * Time: 16:01
 */

class Salary  extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/salary_data' );
    }

    //我的薪资单
    public function mylist($title = 'date',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->salary_data->mylist($admin_id);
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
        $result = $this->salary_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $this->load->view('',$result);

    }

    //员工薪资列表
    public function lists($title = 'date',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->salary_data->index($admin_id);
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
        $result = $this->salary_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );
        $result['users'] = $this->salary_data->get_users($admin_id);
        $this->load->view('',$result);

    }

    //查询条件
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
        $status = $input['status'];
        $where_sql = "";
        $where = [];
        if (!empty($search)){
                $where[] = " real_name = '$search'";
            }
        if (!empty($start_time)){
            $where[] = " date >= $start_time";
        }
        if (!empty($end_time)){
            $where[] = " date <= $end_time";
        }
        if (is_numeric($status)){
            $where[] = " salary_status = $status";
        }
        if($where){
            $where_sql .= ' where '.implode(' and ',$where);
        }

        $condition[] = $where_sql;

        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    public function add()
    {
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->salary_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->salary_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $this->load->view ( '' );
        }
    }

    //编辑
    public function edit( $id = null )
    {
        $data['info'] = $this->salary_data->get_info ( $id );
        $this->load->view ( '@/add', $data );
    }

    public function payroll()
    {
        if ( IS_POST ) {
            $input = $this->input->post();

            $input['ids'] = implode(',',$input['ids']);

            if ( !$this->salary_data->payroll ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->salary_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $this->load->view ( '' );
        }
    }

}