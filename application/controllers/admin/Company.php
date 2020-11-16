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
        $this->load->model ( 'data/admin_data' );
    }

    //企业主体列表
    public function index($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->company_data->index($admin_id);
        $page = max ( 1, $this->input->get ( 'page' ) );

        $input = $this->input->get ();

        if($input['a']){
            $ids = model ( 'data/companyaccount_data' )->get_field_by_where('company_id',['company_account_id'=>$input['search']],true);
            $input['search'] = $ids ? implode(',',array_column($ids,'company_id')):0;
        }

        $condition = $this->parse_query_lists ($input);

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


    /**
     * 导出
     */
    public function daochu_all(){

        $lists = $this->company_data->daochu();

        $data['heard'] = [
            '代理商','公司名称','营业执照图片','广告主联系人姓名','广告主联系人邮箱','时区','BM','开户状态(0审核成功、1审核中、2审核失败)','下户时间','BM API','备注','FB粉丝页链接','域名','所属人'
        ];

        foreach($lists as $k=>$v){
            $data[$k][] = $v['agent'];
            $data[$k][] = $v['company_name'];
            $data[$k][] = $v['business_license_image'];
            $data[$k][] = $v['ad_connect_name'];
            $data[$k][] = $v['ad_connect_email'];
            $data[$k][] = $v['time_zone'];
            $data[$k][] = $v['BM'].' ';
            $data[$k][] = $v['account_status'];
            $data[$k][] = $v['logout_time'];
            $data[$k][] = $v['BMAPI'];
            $data[$k][] = $v['company_remark'];
            $data[$k][] = $v['fanslink'];
            $data[$k][] = $v['domain'];
            $data[$k][] = $v['user_name'];
        }

        $this->_exportExcel($data,'企业主体',14);

    }


    //查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){

            if($input['a']){
                $condition[] = " where id in ($search)";
            }else{
                $condition[] = " where agent like '%{$search}%' or company_name like '%{$search}%' or company_remark like '%{$search}%' or domain like '%{$search}%' or user_name like '%{$search}%'";
            }
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
            $data['users'] = $this->admin_data->get_users ($admin_id);
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
            $data['users'] = $this->admin_data->get_users ($admin_id);
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

