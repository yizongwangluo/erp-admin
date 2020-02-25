<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Goods_sku_data extends \Application\Component\Common\IData{

    /**
     * 查询列表
     * @param int $uid
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function get_list($uid = 0,$where = [],$page = 1,$limit = 17){

        if($where['keyword'] || $uid==1){ //当输入关键词的时候
            $table_b = 'left join admin b on a.u_id=b.id ';
        }else{
            $table_b = ' INNER JOIN (select * from admin_org_temp where u_id=1 GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }

        $condition['total'] = 'COUNT(*) as total';
        $condition['info'] = 'a.*,b.user_name';

        $sql = 'select {{}} from goods_sku a '.$table_b;
        if($where['keyword']){
            $sql.="WHERE a.sku_id=".$where['sku_id'];
        }

        $sql_total = str_replace('{{}}',$condition['total'],$sql);
        $query = $this->db->query($sql_total);
        $total = $query->result_array()['total'];

        $sql .=  'limit '.($page-1)*$limit.','.$limit;
        $sql_info = str_replace('{{}}',$condition['info'],$sql);
        $query = $this->db->query($sql_info);
        $info = $query->result_array();

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
            $this->set_error('请填写SKU编码');return false;
        }
        if(empty($input['norms'])){
            $this->set_error('请填写规格/颜色');return false;
        }
        if(empty($input['img'])){
            $this->set_error('请上传产品图片');return false;
        }

        $alias = $input['alias'];
        $sku = $input['code'];

        $alias_name = explode(',',$alias);

        if(in_array($sku,$alias_name)){
            $this->set_error('sku编码与sku别名重复');return false;
        }

        //判断sku编码是否已存在
        $sql = "select code from goods_sku where id <> $id";
        $code = $this->db->query ( $sql )->result_array ();
        $codes = array_column($code , 'code' );
        if(in_array($sku,$codes)){
            $this->set_error('sku编码已被使用');return false;
        }
        //判断sku编码是否与已存在的sku别名同名
        $sql = "select group_concat(alias) from goods_sku_apply ";
        $re = $this->db->query ( $sql )->result_array ();
        $re = $re[0]['group_concat(alias)'];
        $ress = explode(",",$re);
        if(in_array($sku,$ress)){
            $this->set_error('sku编码与已存在的sku别名同名');return false;
        }

        if(!empty($alias)){
            $alias = explode(',',$alias);
            //判断别名是否已存在
            $sql = "select group_concat(alias) from goods_sku where id <> $id";
            $re = $this->db->query ( $sql )->result_array ();
            $re = $re[0]['group_concat(alias)'];
            $ress = explode(",",$re);

            foreach($alias as $v){
                if(in_array($v,$ress)){
                    $this->set_error('sku别名已被使用');return false;
                }
            }
            //判断别名是否与已存在的sku编码同名
            $sql = "select code from goods_sku";
            $code = $this->db->query ( $sql )->result_array ();
            $codes = array_column($code , 'code' );
            foreach($alias as $v){
                if(in_array($v,$codes)){
                    $this->set_error('sku别名与已存在的sku编码同名');return false;
                }
            }
        }

        $input = array_filter($input);

        $input['status'] = $input['status'] || is_numeric($input['status'])?$input['status'] : 0; //修改审核状态为未审核

        return $this->update($id,$input);
    }

    /**
     * 添加
     * @param array $input
     * @return bool|int
     */
    public function add($input = []){

        if(empty($input['code'])){
            $this->set_error('请填写SKU编码');return false;
        }
        if(empty($input['norms'])){
            $this->set_error('请填写规格/颜色');return false;
        }
        if(empty($input['img'])){
            $this->set_error('请上传产品图片');return false;
        }

        $input = array_filter($input);

        return $this->store($input);
    }

    /**
     * 删除
     * @param int $id
     * @return bool
     */
    public function del($id = 0){

        return $this->delete($id);
    }

    /**
     * 提交审核
     * @param int $id
     * @return bool
     */
    public function to_examine_spuid($id = 0){
        if($id){
            return $this->update($id,['status'=>2],'spu_id');
        }
    }

    /**
     * 修改状态
     * @param int $spu_id
     * @param int $status
     * @return bool
     */
    public function edit_spuid($spu_id = 0,$status = 0){
        if($spu_id){
            return $this->update($spu_id,['status'=>$status],'spu_id');
        }
    }

    /**
     * 同步sku
     * @param array $input
     * @return mixed
     */
    public function synchronization($input = []){
        $insert_query = $this->db->insert_string('goods_sku', $input);
        $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query); //存在则忽略，不存在则添加
        $this->db->query($insert_query);
        return $this->db->affected_rows();
    }

}