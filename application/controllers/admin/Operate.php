<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/6
 * Time: 11:43
 */

class Operate  extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/operate_data' );
        $this->load->model ( 'data/my_data' );
    }

    //运营数据列表
    public function index($title = 'datetime',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->operate_data->index($admin_id);
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
        $result = $this->operate_data->list_page ( $sql, $condition, [$title, $sort], $page, 10 );
        $result['page_html'] = create_page_html ( '?', $result['total'],10 );               $result['users'] = $this->operate_data->get_users($admin_id);
        $result['sum'] = $this->operate_data->get_sum( $sql, $condition );
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
        $user = $input['user'];
        $where_sql = "";
        $where = [];
        if (!empty($user)){
            $where[] = " user_name = '$user'";
        }
        if (!empty($search)){
            $where[] = " (user_name like '%{$search}%' or domain like '%{$search}%')";
        }
        if (!empty($start_time)){
            $where[] = " datetime >= '$start_time'";
        }
        if (!empty($end_time)){
            $where[] = " datetime <= '$end_time'";
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
            if ( !$this->operate_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->operate_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $this->load->view ( '' );
        }
    }

    //上传广告费
    public function edit( $id = null )
    {
        $data['info'] = $this->operate_data->get_info ( $id );
        $this->load->view ( '@/add', $data );
    }

    //运营数据详情页
    public function detail ( $id = null )
    {
        $data['info'] = $this->operate_data->get_info ( $id );
        $user_id = $data['info']['user_id'];
        $shop_id = $data['info']['shop_id'];
        $data['user'] = $this->my_data->get_user ( $user_id );
        $data['domain'] = $this->my_data->get_domain ( $shop_id );
        $this->load->view ( '' , $data );
    }

    //产品成本明细
    public function product_list  ( $id = null )
    {
        $product_list = $this->operate_data->get_product_list( $id );
        echo json_encode(['code'=>0,'msg'=>'ok','data'=>$product_list]);
    }

}