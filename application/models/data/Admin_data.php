<?php

/**
 * 管理员数据
 * User: xiongbaoshan
 * Date: 2016/7/21
 * Time: 15:22
 */
class Admin_data extends \Application\Component\Common\IData
{

    /**
     * 按用户名获取
     * @param $user_name
     * @return array
     */
    public function get_info_by_user_name($user_name){
        return $this->_get_info(['user_name'=>$user_name]);
    }

    /**
     * 加密密码
     * @param $password
     * @return string
     */
    public function encrypt_password($password){
        return md5($password);
    }

    /**
     * 比较密码
     * @param $input
     * @param $real
     * @return bool
     */
    public function compare_password($input,$real){

        return $this->encrypt_password($input)===$real;
    }

    /**
     * 查询用户列表
     */
    public function admin_lists(){
        $sql = 'select id,duties_id,user_name,is_disable from admin';
        return   $this->db->query ( $sql )->result_array ();
    }

    /**
     * 查询某职位下面的用户数量
     */
    public function admin_count_position($pid = 0){
        return $this->count(['duties_id'=>$pid]);
    }

    /**
     * 新增职员
     */
    public function adminUserAdd($input = array()){

        if (empty($input['work_number'])){
            $this->set_error ('工号必填');
            return false;
        }
        if (empty($input['user_name'])){
            $this->set_error ('姓名必填');
            return false;
        }
        if (empty($input['user_password'])){
            $this->set_error ('登录密码必填');
            return false;
        }
        empty($input['pid']) && $input['pid'] = 0;
        empty($input['flag'])&& $input['flag']=1;

        $info = [];
        $info['user_name'] = $input['user_name'];
        $info['user_password'] = $this->encrypt_password($input['user_password']);
        $info['duties_id'] = $input['duties_id'];
        $info['role_id'] = $input['role_id'];
        $info['login_ip'] = $this->input->ip_address();
        $info['login_time'] = time();
        $info['dateline'] = time();
        $info['is_disable'] = $input['is_disable'];
        $info['sex'] = $input['sex'];
        $info['mobile'] = $input['mobile'];
        $info['type'] = $input['type'];
        $info['sex'] = $input['sex'];
        $info['work_number'] = $input['work_number'];
        $info['position'] = $input['position'];

        return $this->store($info);
    }

    public function updateUserAdd($input = array()){
        if (empty($input['work_number'])){
            $this->set_error ('工号必填');
            return false;
        }
        if (empty($input['user_name'])){
            $this->set_error ('姓名必填');
            return false;
        }
        empty($input['pid']) && $input['pid'] = 0;
        empty($input['flag'])&& $input['flag']=1;

        $info = [];
        $info['user_name'] = $input['user_name'];
        !empty($input['user_password'])&&$info['user_password'] = $this->encrypt_password($input['user_password']);
        $info['duties_id'] = $input['duties_id'];
        $info['role_id'] = $input['role_id'];
        $info['login_ip'] = $this->input->ip_address();
        $info['login_time'] = time();
        $info['is_disable'] = $input['is_disable'];
        $info['sex'] = $input['sex'];
        $info['mobile'] = $input['mobile'];
        $info['type'] = $input['type'];
        $info['sex'] = $input['sex'];
        $info['work_number'] = $input['work_number'];
        $info['position'] = $input['position'];
        return $this->update($input['id'],$info);
    }

    /**
     * 本人修改个人信息
     * @param array $input
     * @return bool
     */
    public function updateUserAdd_me($input = array()){
        empty($input['pid']) && $input['pid'] = 0;
        empty($input['flag'])&& $input['flag']=1;

        $info = [];
        !empty($input['user_password'])&&$info['user_password'] = $this->encrypt_password($input['user_password']);
        $info['duties_id'] = $input['duties_id'];
        $info['img'] = $input['thumb_img'];
        $info['login_ip'] = $this->input->ip_address();
        $info['login_time'] = time();
        $info['is_disable'] = $input['is_disable'];
        $info['sex'] = $input['sex'];
        $info['mobile'] = $input['mobile'];
        $info['type'] = $input['type'];
        $info['sex'] = $input['sex'];
        $info['position'] = $input['position'];
        return $this->update($input['id'],$info);
    }

    /**
     * 使用职员id查询详细信息
     * @param int $id
     * @return mixed
     */
    public function get_info_admin_inid($id = 0){
        $sql = 'select *,a.id as userid from admin a LEFT JOIN admininfo_extend b on a.id=b.uid where a.id='.$id;
        $query = $this->db->query($sql);
        $info = $query->row_array();
        return $info;
    }

    /**
     * 使用职员id查询详细信息
     * @param int $id
     * @return mixed
     */
    public function get_info_admin_redis($id = 0,$rules = ''){
        if($id && $id!=1){
            $sql = 'select a.id,a.user_name,a.duties_id,a.sex,a.work_number,b.duties_name from admin a LEFT JOIN duties_list b on a.duties_id = b.id where b.id in ('.$rules.')';
        }else{
            $sql = 'select a.id,a.user_name,a.duties_id,a.sex,a.work_number,b.duties_name from admin a LEFT JOIN duties_list b on a.duties_id = b.id';
        }
        $query = $this->db->query($sql);
        $info = $query->result_array();
        return $info;
    }


    /**
     * 根据组织id 查询用户列表
     * @param int $org_id
     * @return mixed
     */
    public function lists_org_id($org_id = 0){
        if($org_id){
            $sql = 'select * from admin where FIND_IN_SET("'.$org_id.'",org_id) ';
            $query = $this->db->query($sql);
            $info = $query->result_array();
            return $info;
        }
    }

    /**
     * 查询某组织列表中的所有用户
     * @param string $oids
     * @return mixed
     */
    public function get_ulist_in_oid($oids = ''){
        if($oids){
            $oids_arr = explode('|',$oids);

            $sql = "select id,user_name,real_name,org_id from admin where is_disable=0 and org_id REGEXP '(^|,)({$oids})(,|$)'";
            $query = $this->db->query($sql);
            $info = $query->result_array();
            $arr = [];
            foreach($info as $v){
                if($v['org_id']){
                    $c = explode(',',$v['org_id']);
                    foreach($c as $value){
                        if(in_array($value,$oids_arr)){
                            $arr[] = ['u_id'=>$v['id']
                                ,'o_id'=>$value
                                ,'user_name'=>$v['user_name']
                                ,'real_name'=>$v['real_name']
                            ];
                        }
                    }
                }
            }
            return $arr;
        }
    }


}