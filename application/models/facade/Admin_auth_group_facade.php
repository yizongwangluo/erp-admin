<?php

/**
 * 权限组
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 15:07
 */
class Admin_auth_group_facade extends \Application\Component\Common\IFacade
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/admin_auth_group_data' );
	}

	/**
	 * 新增，更新数据！
	 * @param array $in
	 * @return bool
	 */
	public function add ( array $in )
	{
		if ( empty( $in['title'] ) ) {
			$this->set_error ( '请输入权限组名称' );
			return false;
		}
		if ( !is_numeric ($in['status']) ) {
			$this->set_error ( '请选择权限组状态' );
			return false;
		}
		$id = $in['id'];
		$data= array ('title' => $in['title'], 'status' => $in['status']);
		if (!$id){
			if ( !$this->admin_auth_group_data->store ($data) ) {
				$this->set_error ( '数据增加失败，请稍后再试~' );
				return false;
			}
		}else{
			unset($in['id']);
			if (!$this->admin_auth_group_data->update($id,$data)){
				$this->set_error ('数据更新失败，请稍后再试！');
				return false;
			}
		}
		return true;
	}
}