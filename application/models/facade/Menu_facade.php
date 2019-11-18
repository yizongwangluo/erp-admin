<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 11:26
 */
class Menu_facade extends \Application\Component\Common\IFacade{
	public function __construct ()
	{
		parent::__construct ();
		$this->load->model('data/menu_data');
	}

	public function getMenuList($condition='',$orderby='desc'){
		$result = $this->menu_data->lists($condition,['sort',$orderby]);
		return $result;
	}

	public function addMenu($input){
		if (!isset($input['pid'])){
			$this->set_error ('上级菜单参数必填！');
			return false;
		}
		if (empty($input['name'])){
			$this->set_error ('菜单名称必填');
			return false;
		}
		empty($input['pid']) && $input['pid'] = 0;
		if ($input['pid'] != 0){
			if (empty($input['url'])){
				$this->set_error ('URL地址必填');
				return false;
			}
		}
		if (!isset($input['status'])){
			$this->set_error ('状态必须选择');
			return false;
		}
		unset($input['id']);
		$input = array_filter ($input);
	    return	$this->menu_data->store($input);
	}

	public function updateMenu($input){
		if (!isset($input['pid'])){
			$this->set_error ('上级菜单参数必填！');
			return false;
		}
		if (empty($input['name'])){
			$this->set_error ('菜单名称必填');
			return false;
		}
		if ($input['pid'] != 0){
			if (empty($input['url'])){
				$this->set_error ('URL地址必填');
				return false;
			}
		}
		if (!isset($input['status'])){
			$this->set_error ('状态必须选择');
			return false;
		}
		$pk = $input['id'];
		unset($input['id']);
		return	$this->menu_data->update($pk,$input);
	}

	public function delMenu($id){
		$result = $this->getMenuList (array ('pid'=>$id));
		if (!empty($result)){
			$this->set_error ('请先删除栏目下子栏目，再进行操作');
			return false;
		}else{
		 return	$this->menu_data->delete($id);
		}
	}
}