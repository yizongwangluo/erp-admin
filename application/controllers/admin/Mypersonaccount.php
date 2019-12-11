<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/4
 * Time: 17:54
 */

class Mypersonaccount extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/personaccount_data' );
    }

    //我的个人账号
    public function index($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->personaccount_data->index($admin_id);
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
        $result = $this->personaccount_data->list_page ( $sql, $condition, [$title, $sort], $page, 5 );
        $result['page_html'] = create_page_html ( '?', $result['total'],5 );
        $this->load->view('',$result);

    }

    //查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where person_username like '%{$search}%' or RdoIp like '%{$search}%' or Rdo_username like '%{$search}%' or real_name like '%{$search}%' or person_remark like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    public function cookies( $id = null )
    {
        $data['info'] = $this->personaccount_data->get_info ( $id );
        $data['info'] = $this->personaccount_data->get_info ( $id );
        $this->load->view ( '' ,$data);
    }
}