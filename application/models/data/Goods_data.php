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
    public function get_list($uid = 0,$where = [],$page = 1,$limit = 17){

        if($where['keyword'] || $uid==1){ //当输入关键词的时候
            $table_b = 'left join admin b on a.u_id=b.id ';
        }else{
            $table_b = ' INNER JOIN (select * from admin_org_temp where u_id=1 GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }

        $condition['total'] = 'COUNT(*) as total';
        $condition['info'] = 'a.*,b.user_name';

        $sql = 'select {{}} from goods a '.$table_b;


        if(is_numeric($where['status'])){
            $sql_where[] = ' a.status='.$where['status'];
        }
        if($where['keyword']){
            $sql_where[]=" FIND_IN_SET('".$where['keyword']."',a.keyword)";
        }

        if($sql_where){
            $sql .= ' where '.implode(' and ',$sql_where);
        }

        $sql_total = str_replace('{{}}',$condition['total'],$sql);
        $query = $this->db->query($sql_total);
        $total = $query->result_array()[0]['total'];
        $info = [];

        if($total){ //有数据时，查询列表
            $sql .=  ' limit '.($page-1)*$limit.','.$limit;
            $sql_info = str_replace('{{}}',$condition['info'],$sql);
            $query = $this->db->query($sql_info);
            $info = $query->result_array();
        }

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
        if(empty($input['img'])){
            $this->set_error('请上传产品图片');return false;
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
            $this->set_error('请填写SPU编码');return false;
        }
        if(empty($input['name'])){
            $this->set_error('请填写产品名称');return false;
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
    public function to_examine($id = 0){
        if($id){
            return $this->update($id,['status'=>2]);
        }
    }

}