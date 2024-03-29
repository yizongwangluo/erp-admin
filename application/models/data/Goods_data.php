<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Goods_data extends \Application\Component\Common\IData{

    /**
     * 查询列表
     * @param int $uid
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function get_list($uid = 0,$where = [],$page = 1,$limit = 17,$is_daochu = false){

//        if($where['name'] || $uid==1){ //当输入关键词的时候
            $table_b = 'left join admin b on a.u_id=b.id ';
        /*}else{
            $table_b = ' INNER JOIN (select s_u_id,s_user_name as user_name from admin_org_temp where u_id='.$uid.' GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }*/

        $condition['total'] = 'COUNT(*) as total';
        $condition['info'] = 'a.*,b.user_name';

        if(is_numeric($where['status'])){
            $sql_where[] = ' a.status='.$where['status'];
        }
        if(is_numeric($where['category_id'])){
            $sql_where[] = ' a.category_id='.$where['category_id'];
        }

        //用sku别名/编码/时间搜索
        if($where['a']=='sku_code' || $where['a']=='sku_code_mh' || $where['a']=='sku_alias'){

            if(empty($where['name']) && empty($where['datetime'])){

                if($is_daochu) return [];

                return array(
                    'page_count' => 0,
                    'page_num' => 0,
                    'page_size' => 0,
                    'total' => 0,
                    'data' => []
                );
            }

            $sql = 'select {{}} from goods a left JOIN  goods_sku b  on a.id=b.spu_id  LEFT JOIN admin c on b.u_id=a.id';

            if($where['name']){
                if( $where['a']=='sku_code'){
                    $sql_where[]=" b.code = '".$where['name']."'";
                }elseif($where['a']=='sku_alias'){
                    $sql_where[]=" b.alias like '%".$where['name']."%'";
                }elseif($where['a']=='sku_code_mh'){
                    $sql_where[]=" b.code like '%".$where['name']."%'";
                }
            }

            if($where['datetime']){

                $datetime = explode(' - ',$where['datetime']);

                $sql_where[]=" b.edittime >= '".strtotime($datetime[0])."'";
                $sql_where[]=" b.edittime <= '".strtotime($datetime[1])."'";
            }

            if($sql_where){
                $sql .= ' where '.implode(' and ',$sql_where);
            }

            $sql_total = str_replace('{{}}',$condition['total'],$sql);
            $query = $this->db->query($sql_total);
            $total = $query->result_array()[0]['total'];
            $info = [];

            if($total){ //有数据时，查询列表
                if($is_daochu){
                    $sql .=  ' order by a.id desc';
                }else{
                    $sql .=  ' order by a.id desc limit '.($page-1)*$limit.','.$limit;
                }
                $sql_info = str_replace('{{}}','a.*,b.code as sku_code,b.norms_name,b.norms,b.norms_name1,b.norms1,b.alias,b.price,b.weight,c.user_name',$sql);
                $query = $this->db->query($sql_info);
                $info = $query->result_array();
            }

        }else{

            $sql = 'select {{}} from goods a '.$table_b;

            if($where['name']){
                if($where['a']=='name'){
                    $sql_where[]=" a.name = '".$where['name']."'";
                }elseif($where['a']=='code'){
                    $sql_where[]=" a.code = '".$where['name']."'";
                }
            }

            if($where['datetime']){

                $datetime = explode(' - ',$where['datetime']);

                $sql_where[]=" a.edittime >= '".strtotime($datetime[0])."'";
                $sql_where[]=" a.edittime <= '".strtotime($datetime[1])."'";
            }

            if($sql_where){
                $sql .= ' where '.implode(' and ',$sql_where);
            }

            $sql_total = str_replace('{{}}',$condition['total'],$sql);
//            echo $sql_total;exit;
            $query = $this->db->query($sql_total);
            $total = $query->result_array()[0]['total'];
            $info = [];

            if($total){ //有数据时，查询列表
                if($is_daochu){
                    $sql .=  ' order by id desc';
                }else{
                    $sql .=  ' order by id desc limit '.($page-1)*$limit.','.$limit;
                }
                $sql_info = str_replace('{{}}',$condition['info'],$sql);
                $query = $this->db->query($sql_info);
                $info = $query->result_array();

                $hh_  = $is_daochu? "\n":'<br/>';

                foreach($info as $k=>$v){
                    $sku_list = $this->db->query('select a.code,a.norms_name,a.norms,a.norms_name1,a.norms1,a.alias,a.price,a.weight,a.status,b.user_name,a.is_mabang,a.id as sku_id from goods_sku a  LEFT JOIN  admin b on a.u_id=b.id where a.spu_id= '.$v['id'])->result_array();

                    $info[$k]['sku_code'] = implode($hh_,array_column($sku_list,'code'));
                    $info[$k]['norms_name'] = implode($hh_,array_column($sku_list,'norms_name'));
                    $info[$k]['norms'] = implode($hh_,array_column($sku_list,'norms'));
                    $info[$k]['norms_name1'] = implode($hh_,array_column($sku_list,'norms_name1'));
                    $info[$k]['norms1'] = implode($hh_,array_column($sku_list,'norms1'));
                    $info[$k]['alias'] = implode($hh_,array_column($sku_list,'alias'));
                    $info[$k]['price'] = implode($hh_,array_column($sku_list,'price'));
                    $info[$k]['weight'] = implode($hh_,array_column($sku_list,'weight'));
                    $info[$k]['user_name'] = implode($hh_,array_column($sku_list,'user_name'));
                    $is_mabang = array_column($sku_list,'is_mabang');
                    foreach($is_mabang as $key=>$value){
                        $is_mabang[$key] = $value?'<a class="tb_mabang" data_id="'.$sku_list[$key]['sku_id'].'" title="更新">已同步</a>':'<a class="tb_mabang" style="color: red"  data_id="'.$sku_list[$key]['sku_id'].'"  title="上传"  >未同步</a>';
                    }
                    $info[$k]['is_mabang'] = implode($hh_,$is_mabang);
                }
            }
        }

        if($is_daochu) return $info;

        return array(
            'page_count' => $this->page->get_page_count(),
            'page_num' => $page,
            'page_size' => $limit,
            'total' => $total,
            'data' => $info
        );
    }

    /**
     * 修改
     * @param int $id
     * @param array $input
     * @return bool
     */
    public function edit($id = 0,$input = []){

        if(empty($id)){
            $this->set_error('系统错误');return false;
        }
        if(empty($input['code'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(empty($input['name'])){
            $this->set_error('请填写产品名称');return false;
        }
        if(empty($input['name_en'])){
            $this->set_error('请填写产品英文名称');return false;
        }
        if(empty($input['dc_name'])){
            $this->set_error('请填写中文报关名');return false;
        }
        if(empty($input['dc_name_en'])){
            $this->set_error('请填写英文报关名');return false;
        }
        if(empty($input['img'])){
            $this->set_error('请上传产品图片');return false;
        }
        if(empty($input['warehouse_id'])){
            $this->set_error('请选择仓库');return false;
        }

        //判断code是否重复
        if($this->removal(['id'=>$id,'code'=>$input['code']])){
            $this->set_error('该spu编码已存在！');return false;
        }

        //$input = array_filter($input);

        $input['status'] = $input['status'] || is_numeric($input['status'])?$input['status'] : 0; //修改审核状态为未审核

        $input['edittime'] = time();

        return $this->update($id,$input);
    }

    /**
     * 添加
     * @param array $input
     * @return bool|int
     */
    public function add($input = []){

        if(empty($input['code'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(empty($input['name'])){
            $this->set_error('请填写产品名称');return false;
        }
       /* if(empty($input['img'])){
            $this->set_error('请上传产品图片');return false;
        }*/

        //判断code是否重复
        if($this->removal(['code'=>$input['code']])){
            $this->set_error('该spu编码已存在！');return false;
        }
        if(empty($input['warehouse_id'])){
            $this->set_error('请选择仓库');return false;
        }


        $input = array_filter($input);

        //时间
        $time = time();
        $arr['addtime'] = $time;
        $arr['edittime'] = $time;

        return $this->store($input);
    }


    /**
     * 导入
     * @param array $input
     * @return bool|int
     */
    public function excelSave($input = []){

        if(empty($input['code'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(empty($input['name'])){
            $this->set_error('请填写产品名称');return false;
        }

        //判断code是否重复
        $info = $this->find(['code'=>$input['code']]);
        $input = array_filter($input);
        $time = time();
        $arr['edittime'] = $time;

        if($info){ //修改
            $this->update($info['id'],$input);
            return $info['id'];
        }else{ //新增
            $arr['addtime'] = $time;//时间
            return $this->store($input);
        }
    }

    /**
     * 删除
     * @param int $id
     * @return bool
     */
    public function del($id = 0){

        //删除子产品
        $sql = "delete from goods_sku where spu_id=".$id;
        $query = $this->db->query($sql);

        return $this->delete($id);
    }

    /**
     * 同步
     * @param array $apply_info
     * @return bool
     */
    public function synchronization($apply_info = []){

        $info = $this->find(['code'=>$apply_info['code']]);
        //过滤参数
        unset($apply_info['id']);
        unset($apply_info['sales_volume']);
//        unset($apply_info['u_id']);
        unset($apply_info['addtime']);
        unset($apply_info['edittime']);
        unset($apply_info['status']);
        unset($apply_info['type']);
        //过滤参数end

        $time = time();
        $apply_info['edittime'] = $time;
        if($info){ //修改
            if(!$this->update($info['id'],$apply_info)){
                $this->set_error('(修改)同步失败，请稍后重试');return false;
            }
        }else{ //新增
            $apply_info['addtime'] = $time;
            $info['id'] = $this->store($apply_info);
            if(!$info['id']){
                $this->set_error('(新增)同步失败，请稍后重试');return false;
            }
        }
        return $info['id'];
    }

    /**
     * 根据id查询商品详情
     * @param string $ids
     * @return bool
     */
    public function get_list_inids($ids = ''){

        if(empty($ids)){
            return false;
        }

        $spu_list = $this->db->query('select * from goods where id in ('.$ids.')')->result_array();

        return $spu_list;
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