<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Goods_apply_data extends \Application\Component\Common\IData{

    public function edit($id,$arr){

        $sql = "select alias from goods_sku_apply where spu_id = $id";
        $alias = $this->db->query($sql)->result_array();
        $alias = array_column($alias , 'alias' );

        //判断新增别名是否重复
        if (count($alias) != count(array_unique($alias))) {
            $this->set_error('新增sku别名重复,请检查！');return false;
        }
        return $this->update($id,$arr);
    }
    /**
     * 添加
     * @param int $u_id
     * @param array $arr
     * @return bool
     */
    public function add($u_id = 0,$arr = []){

        $status = $arr['status'] ? $arr['status']:0;

        $sku_list = json_decode($arr['data_sku'],true);
        unset($arr['data_sku']);

        if(empty($arr['name'])){
            $this->set_error('请填写产品名称');return false;
        }

        $alias_name = [];
        foreach($sku_list as $k=>$value){
            if(!empty($value['alias'])){
                $exc = $this->is_alias($value['alias']);
                if(!$exc){
                    return false;
                }
                array_push($alias_name,$value['alias']);
            }
        }
        //判断新增别名是否重复
        if (count($alias_name) != count(array_unique($alias_name))) {
            $this->set_error('新增sku别名重复,请检查！');return false;
        }

        //时间
        $time = time();
        $arr['addtime'] = $time;
        $arr['edittime'] = $time;

        $arr = array_filter($arr);
        $id = $this->store($arr);

        if(!$id){
            $this->set_error('添加失败，请稍后重新添加');return false;
        }

        if($id && count($sku_list)){ //添加spu成功，添加sku
            $sql = 'INSERT INTO goods_sku_apply (spu_id,code,norms,img,price,size,weight,cycle,information,remarks,u_id,type,is_real,status,alias)  VALUES ';
            $sql_val = [];
            foreach($sku_list as $k=>$value){
                $code = '';
                $is_real = $value['is_real']?$value['is_real']:0;
                $type = $value['type']?$value['type']:0;
                if($is_real){ //测试sku
                    $code = date('YmdHis').'_'.$id.'_'.$k;
                }
                $sql_val[] = "({$id},'{$code}','{$value['norms']}','{$value['img']}',{$value['price']},'{$value['size']}',{$value['weight']},{$value['cycle']},'{$value['information']}','{$value['remarks']}',{$u_id},{$type},{$is_real},{$status},'{$value['alias']}')";
            }

            $sql.=implode(',',$sql_val);
            $query = $this->db->query($sql);
            if($this->db->affected_rows()<=0){
                $this->set_error('添加SKU失败,请稍后重新添加');return false;
            }
        }
        return true;
    }


    /**
     * 获取列表
     * @param int $uid
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function get_list($uid = 0,$where = [],$page = 1,$limit = 15){

        if($where['name'] || $uid==1){ //当输入关键词的时候
            $table_b = 'left join admin b on a.u_id=b.id ';
        }else{
            $table_b = ' INNER JOIN (select s_u_id,s_user_name as user_name from admin_org_temp where u_id='.$uid.' GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }

        $condition['total'] = 'COUNT(*) as total';
        $condition['info'] = 'a.*,b.user_name';

        $sql = 'select {{}} from goods_apply a '.$table_b;

        if(is_numeric($where['status'])){
            $sql_where[] = ' a.status='.$where['status'];
        }
        if($where['category_id']){
            $sql_where[]= ' a.category_id='.$where['category_id'];
        }
        if($where['name']){
            $sql_where[]= ' a.name= "'.$where['name'].'" ';
        }

        if($sql_where){
            $sql .= ' where '.implode(' and ',$sql_where);
        }

        $sql_total = str_replace('{{}}',$condition['total'],$sql);
        $query = $this->db->query($sql_total);
        $total = $query->result_array()[0]['total'];
        $info = [];

        if($total){ //有数据时，查询列表
            $sql .=  ' order by id desc limit '.($page-1)*$limit.','.$limit;
            $sql_info = str_replace('{{}}',$condition['info'],$sql);
            $query = $this->db->query($sql_info);
            $info = $query->result_array();

            foreach($info as $k=>$v){
                $sku_list = $this->db->query('select code,norms,alias,price,weight,status,code from goods_sku_apply where spu_id= '.$v['id'])->result_array();
                $info[$k]['sku_code'] = implode('<br/>',array_column($sku_list,'code'));
                $info[$k]['norms'] = implode('<br/>',array_column($sku_list,'norms'));
                $info[$k]['alias'] = implode('<br/>',array_column($sku_list,'alias'));
                $info[$k]['price'] = implode('<br/>',array_column($sku_list,'price'));
                $info[$k]['weight'] = implode('<br/>',array_column($sku_list,'weight'));
            }
        }

        return array(
            'page_count' => $this->page->get_page_count(),
            'page_num' => $page,
            'page_size' => $limit,
            'total' => $total,
            'data' => $info
        );

    }


    public function del($id = 0){

        //删除关联sku
        $this->db->delete('goods_apply',array('spu_id'=>$id));

        //删除spu
        return $this->delete($id);
    }

    public function isset_alias(){
        // 拿到前端post过来的数据
        $alias = isset($_POST["alias"]) ? $_POST["alias"] : "";
        if ($alias == "") {
            // 使用exit函数进行返回
//            exit(json_encode(array("flag" => false, "msg" => "查询信息错误")));
        }
        else {
            //前端获取到的别名以，分割为数组
            $alias = explode(',',$alias);
            //判断别名是否已存在
            $sql = "select group_concat(alias) from goods_sku_apply ";
            $re = $this->db->query ( $sql )->result_array ();
            $re = $re[0]['group_concat(alias)'];
            $ress = explode(",",$re);
            foreach($alias as $v){
                if(in_array($v,$ress)){
                    $result = 1;
                    break;
                }
            }
            //判断别名是否与已存在的sku编码同名
            $sql = "select code from goods_sku";
            $code = $this->db->query ( $sql )->result_array ();
            $codes = array_column($code , 'code' );
            foreach($alias as $v){
                if(in_array($v,$codes)){
                    $result = 2;
                    break;
                }
            }

            if ($result == 0) {
                exit(json_encode(array("flag" => true, "msg" => 0)));
            }
            else if ($result == 1){
                exit(json_encode(array("flag" => true, "msg" => 1)));
            }
            else if ($result == 2){
                exit(json_encode(array("flag" => true, "msg" => 2)));
            }
        }
    }

    public function  is_alias($str = ''){
        $alias = explode(',',$str);
        //判断别名是否已存在
        $sql = "select group_concat(alias) from goods_sku_apply ";
        $re = $this->db->query ( $sql )->result_array ();
        $re = $re[0]['group_concat(alias)'];
        $ress = explode(",",$re);

        foreach($alias as $v){
            if(in_array($v,$ress)){
                $this->set_error('sku别名已存在,请检查！');return false;
            }
        }
        //判断别名是否与已存在的sku编码同名
        $sql = "select code from goods_sku";
        $code = $this->db->query ( $sql )->result_array ();
        $codes = array_column($code , 'code' );
        foreach($alias as $v){
            if(in_array($v,$codes)){
                $this->set_error('sku别名与已存在的sku编码同名,请检查！');return false;
            }
        }
        return true;
    }

    public function edit_alias($str = '',$id = ''){
        $alias = explode(',',$str);
        //判断别名是否已存在
        if($id == ''){
            $sql = "select group_concat(alias) from goods_sku_apply ";
        }else{
            $sql = "select group_concat(alias) from goods_sku_apply where id <> $id";
        }
        $re = $this->db->query ( $sql )->result_array ();
        $re = $re[0]['group_concat(alias)'];
        $ress = explode(",",$re);

        foreach($alias as $v){
            if(in_array($v,$ress)){
                $this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已被使用');
            }
        }
        //判断别名是否与已存在的sku编码同名
        $sql = "select code from goods_sku";
        $code = $this->db->query ( $sql )->result_array ();
        $codes = array_column($code , 'code' );
        foreach($alias as $v){
            if(in_array($v,$codes)){
                $this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名与已存在的sku编码同名');
            }
        }
    }

    public function isset_code($str = ''){
        //判断sku编码是否已存在
        $sql = "select code from goods_sku ";
        $code = $this->db->query ( $sql )->result_array ();
        $codes = array_column($code , 'code' );
        if(in_array($str,$codes)){
            $this->output->ajax_return(AJAX_RETURN_FAIL,'sku编码已被使用');
        }
        //判断sku编码是否与已存在的sku别名同名
        $sql = "select group_concat(alias) from goods_sku_apply ";
        $re = $this->db->query ( $sql )->result_array ();
        $re = $re[0]['group_concat(alias)'];
        $ress = explode(",",$re);
        if(in_array($str,$ress)){
            $this->output->ajax_return(AJAX_RETURN_FAIL,'sku编码与已存在的sku别名同名');
        }
    }


    /**
     * 判断是否已存在该数据
     * @param array $input
     * @return bool
     */
    public function removal($input = array()){

        $data = [];

        $data['id !=']      = $input['id'] ? $input['id']:'';
        $data['code']    	 = $input['code'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
    }
}