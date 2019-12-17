<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/31 0031
 * Time: 14:24
 */
class Admin_user_org_data extends \Application\Component\Common\IData{

	public function __construct ()
	{
		parent::__construct ();
	}


	public function edit($id = 0,$auth_rule_ids = []){

		if(!is_numeric($id)){
			$this->set_error('未指定正确的用户');return false;
		}

		$lists = $this->get_field_by_where('o_id',['u_id'=>$id],true);

		$lists = $lists ? $lists:[];
		$lists  = array_column($lists,'o_id');

		$a = array_diff($lists,$auth_rule_ids); //删除
		$b = array_diff($auth_rule_ids,$lists); //新增

		if(!$a && !$b){
			return true;
		}

		try {

			$this->db->trans_strict(FALSE);
			$this->db->trans_begin();

			if(is_array($a) && !empty($a)){ //删除组织结构
				$ids = implode(',',$a);
				$sql = 'delete from admin_user_org where u_id = '.$id.' and o_id in ('.$ids.')';
				$query = $this->db->query($sql);
				if($this->db->affected_rows()<=0){
					$this->set_error('删除职位失败');return false;
				}
			}

			if(is_array($b) && !empty($b)){ //新增组织结构
				$sql = 'INSERT INTO admin_user_org (u_id,o_id)  VALUES ';
				foreach($b as $v){
					$sql .= '('.$id.','.$v.'),';
				}

				$sql = rtrim($sql,',');
				$query = $this->db->query($sql);
				if($this->db->affected_rows()<=0){
					$this->set_error('添加职位失败');return false;
				}
			}

			$this->db->trans_complete();
			return true;

		}catch(PDOException $e) {
			$this->db->trans_rollback();
			exit($e->getMessage());
		}
	}

	/**
	 * 根据组织id 查询用户id
	 * @param string $ids
	 * @return mixed
	 */
	public function list_in_ids($ids = ''){
		if($ids){
			$sql = 'select a.u_id,a.o_id,b.user_name,b.real_name from admin_user_org a LEFT JOIN  admin b on a.u_id = b.id where b.is_disable=0 and a.o_id in ('.$ids.')';
			$query = $this->db->query($sql);
			$info = $query->result_array();
			return $info;
		}
	}


	/**
	 * 获取所有权限
	 * @param int $uid
	 * @return mixed
	 */
	public function get_user_org($uid = 0){
		if($uid){
			$sql = 'select b.id from admin_user_org a LEFT JOIN admin_organization b on a.o_id=b.id where a.u_id='.$uid.' and b.status=1';
			$query = $this->db->query($sql);
			$info = $query->result_array();
			return $info;
		}
	}
}