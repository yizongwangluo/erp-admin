<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 17:35
 */
class admin_user_org extends \Application\Component\Common\AdminPermissionValidateController{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/admin_organization_data' );
        $this->load->model ( 'data/admin_user_org_data' );
    }

    /**
     * 节点授权
     */
    public function auth_node ( $id = null )
    {
        $this->load->view ( '', ['id' => $id] );
    }

    /**
     * 获取json
     */
    public function getjson ()
    {
        $id  = input ('id');
        $auth_rules = $this->admin_user_org_data->get_field_by_where (['o_id'],['u_id'=>$id],true);
        if($auth_rules){
            $auth_rules = array_column($auth_rules,'o_id');
        }

        $auth_rule_list = $this->admin_organization_data->get_field_by_where ( 'id,pid,name', ['status'=>1] ,true);
        foreach ( $auth_rule_list as $key => $value ) {
            in_array ( $value['id'], $auth_rules) && $auth_rule_list[$key]['checked'] = true;
        }
        echo_json ($auth_rule_list);
    }

    /**
     * 更新
     */
    public function updateAuthGroupRule()
    {
        if (IS_POST) {
            $id = input ('id');
            $auth_rule_ids = input ('auth_rule_ids');

            $ret = $this->admin_user_org_data->edit($id,$auth_rule_ids);

            if ( $ret) {
                $this->output->ajax_return (AJAX_RETURN_SUCCESS,'操作成功');
            } else {
                $this->output->ajax_return (AJAX_RETURN_FAIL,$this->admin_user_org_data->get_error());
            }
        }
    }

}