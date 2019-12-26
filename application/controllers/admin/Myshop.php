<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/3
 * Time: 11:03
 */

class Myshop extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/shop_data' );
        $this->load->model ( 'data/my_data' );
    }

    //我的店铺
    public function index ($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->shop_data->index ($admin_id);
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

    //查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where domain like '%{$search}%' or company_name like '%{$search}%' or user_name like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    //店铺详情
    public function detail ( $id = null )
    {
        $data['info'] = $this->shop_data->get_info ( $id );
        $user_id = $data['info']['user_id'];
        $company_id = $data['info']['company_id'];
        $data['user'] = $this->my_data->get_user ( $user_id );
        $data['company'] = $this->my_data->get_company ( $company_id );
        $this->load->view ( '', $data );
    }
}