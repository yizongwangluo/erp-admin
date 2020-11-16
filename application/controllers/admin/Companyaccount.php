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


    /**
     * 导出
     */
    public function daochu(){

        $lists = $this->companyaccount_data->daochu();

        $data['heard'] = [
            '代理商','域名','ID','企业账户ID','店铺ID','店铺','是否解限','状态（0正常，1封户，2申诉中）','备注','所属人'
        ];

        foreach($lists as $k=>$v){
            $data[$k][] = $v['agent'];
            $data[$k][] = $v['domain'];
            $data[$k][] = $v['id'];
            $data[$k][] = $v['company_account_id'].' ';
            $data[$k][] = $v['shop_id'];
            $data[$k][] = $v['shop_domain'];
            $data[$k][] = $v['isunlock'];
            $data[$k][] = $v['status'];
            $data[$k][] = $v['companyaccount_remark'];
            $data[$k][] = $v['user_name'];
        }

        $this->_exportExcel($data,'企业账号',10);

    }


}