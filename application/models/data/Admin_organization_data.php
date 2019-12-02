<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/31 0031
 * Time: 14:24
 */
class Admin_organization_data extends \Application\Component\Common\IData{

	public function __construct ()
	{
		parent::__construct ();
	}

	/**
	 * 添加组织结构
	 * @param array $input
	 * @return bool
	 */
	public function add($input = []){

		$data = [];//声明数组

		if(empty($input['name'])){
			$this->set_error('请填写组织名称');return false;
		}

		if($input['pid']){ //有父级的时候，查询父级信息
			$p_info = $this->info_id($input['pid']);

			if(!$p_info){
				$this->set_error('不存在该上级组织');return false;
			}

			$data['level'] = $p_info['level']+1;
		}

		if($this->removal($input)){
			$this->set_error('该级别下《'.$input['name'].'》已存在');return false;
		}

		$data['name'] 	 = $input['name'];
		$data['pid'] 	 = $input['pid']?$input['pid']:0;
		$data['status'] = $input['status'];

		return $this->store($data);

	}


	public function del($id = 0){
		if(!is_numeric($id)){
			$this->set_error('未指定应删除的数据，无法删除');return false;
		}
		//查询是否存在下级
		if($this->find(['pid'=>$id])){
			$this->set_error('该结构下还有下级成员，请先删除下级组织');return false;
		}
		//删除
		$ret = $this->delete($id);
		if(!$ret){
			$this->set_error('删除失败！');return false;
		}

		return true;
	}


	/**
	 * 根据id查询信息
	 * @param int $id
	 * @return array
	 */
	public function info_id($id = 0){

		$info = $this->get_info($id);

		return $info;
	}

	/**
	 * 修改
	 * @param array $input
	 */
	public function edit($input = []){

	}

	/**
	 * 判断是否已存在该数据
	 * @param array $input
	 * @return bool
	 */
	public function removal($input = array()){

		$data = [];

		$data['id !=']      = $input['id'] ? $input['id']:'';
		$data['name']    	 = $input['name'];
		$data['p_id']    	 = $input['p_id'];

		$data = array_filter($data); //过滤空白数组

		$count = $this->count($data);

		return $count>0;
	}


	/**
	 * 根据uid查询
	 * @param int $uid
	 * @return mixed
	 */
	public function lists_in_uid($uid = 0){
		$sql = 'select b.id from admin_user_org a LEFT JOIN admin_organization b on a.o_id = b.id where a.u_id = '.$uid;
		$query = $this->db->query($sql);
		$info = $query->result_array();
		return $info;
	}


	/**
	 * 根据pid查询
	 * @param string $pids
	 * @return mixed
	 */
	public function lists_in_pids($pids = ''){
		$sql = 'select id from admin_organization where status=1 and  pid in ('.$pids.')';
		$query = $this->db->query($sql);
		$info = $query->result_array();
		return $info;
	}

}