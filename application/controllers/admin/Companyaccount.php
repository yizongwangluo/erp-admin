<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/3
 * Time: 11:107
 */

class Companyaccount extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/companyaccount_data' );
        $this->load->model ( 'data/apply_data' );
    }

    //企业账号查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where company_account_id like '%{$search}%' or companyaccount_remark like '%{$search}%' or domain like '%{$search}%' or user_name like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    //新增企业账号
    public function add(){
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->companyaccount_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->companyaccount_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $company_id = $_SERVER['QUERY_STRING'];
            $data['domains'] = $this->companyaccount_data->get_domain ( $company_id );
            $this->load->view ( '' ,$data);
        }

    }

    //编辑企业账号
    public function edit( $id = null )
    {
        $company_id = $_SERVER['QUERY_STRING'];
        $data['info'] = $this->companyaccount_data->get_info ( $id );
        $data['domains'] = $this->companyaccount_data->get_domain ( $company_id );
        $this->load->view ( '@/add', $data );
    }

    //删除企业账号
    public function del()
    {
        $id = (int)$this->input->post ( 'id' );
        if (!$this->companyaccount_data->delete($id)) {
            $this->output->ajax_return(AJAX_RETURN_FAIL, '删除失败');
        }
        $this->output->ajax_return(AJAX_RETURN_SUCCESS, '操作成功');
    }

}