<?php

class Admin_org_temp_data extends \Application\Component\Common\IData{

	public function __construct ()
	{
		parent::__construct ();
	}

	/**
	 * 批量添加
	 * @param int $u_id
	 * @param array $arr
	 * @return bool
	 */
	public function add_arr($u_id = 0,$arr = []){

		if($u_id && count($arr)){

			//删除临时表数据
			$this->db->query('delete from  admin_org_temp where u_id='.$u_id);

			//添加关联数据
			$sql = 'INSERT INTO admin_org_temp (u_id,s_u_id,s_o_id,s_user_name,s_real_name )  VALUES ';
			foreach($arr as $v){
				$sql .= '('.$u_id.','.$v['u_id'].','.$v['o_id'].',"'.$v['user_name'].'","'.$v['real_name'].'"),';
			}
			$sql = rtrim($sql,',');
			$query = $this->db->query($sql);
			if($this->db->affected_rows()<=0){
				return false;
			}
			return true;
		}
	}
}