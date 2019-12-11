<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/3
 * Time: 17:51
 */

class mycompanyaccount extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/companyaccount_data' );
    }

    //我的企业账号
    public function index($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->companyaccount_data->index($admin_id);
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
        $result = $this->companyaccount_data->list_page ( $sql, $condition, [$title, $sort], $page, 5 );
        $result['page_html'] = create_page_html ( '?', $result['total'],5 );
        $this->load->view('',$result);

    }

    //查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where company_account_id like '%{$search}%' or company_name like '%{$search}%' or companyaccount_remark like '%{$search}%' or domain like '%{$search}%' or real_name like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }
}