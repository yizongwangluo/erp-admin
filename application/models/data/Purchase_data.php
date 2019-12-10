<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Purchase_data extends \Application\Component\Common\IData{

    public function lists_page_uid($uid = 0,$where = [],$page = 1,$limit = 17){

        if($uid==0){
            $sql = 'select {{}} from purchase LEFT join admin b on a.u_id=b.id';
        }else{
            $sql = 'select {{}} from purchase a INNER JOIN (select s_u_id,s_user_name as user_name from admin_org_temp where u_id=1 GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }

        $sql_where =[];
        if(is_numeric($where['status'])){
            $sql_where[] = ' a.status='.$where['status'];
        }

        if($sql_where){
            $sql .= ' where '.implode(' and ',$sql_where);
        }

        $sql_total = str_replace('{{}}','count(*) as total',$sql);
        $query = $this->db->query($sql_total);
        $total = $query->result_array()[0]['total'];
        $info = [];

        if($total){ //有数据时，查询列表
            $sql .=  ' limit '.($page-1)*$limit.','.$limit;
            $sql_info = str_replace('{{}}','a.*,b.user_name',$sql);
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
     * 添加
     * @param array $input
     * @return bool|int
     */
    public function add($input = []){

        if(!$input['u_id']){
            $this->set_error('未读取到当前操作管理员');return false;
        }
        if(!$input['sku_id']){
            $this->set_error('sku编号必填');return false;
        }
        if(!$input['add_sku_number']){
            $this->set_error('补货数必填');return false;
        }
        if(!$input['days']){
            $this->set_error('备货天数必填');return false;
        }

        $data = [];
        $data['sku_id'] = $input['sku_id'];
        $data['add_sku_number'] = $input['add_sku_number'];
        $data['remarks'] = $input['remarks'];
        $data['days'] = $input['days'];
        $data['u_id'] = $input['u_id'];
        $data['addtime'] = time();

        return $this->store($data);
    }

    /**
     * 修改
     * @param int $id
     * @param array $input
     * @return bool
     */
    public function edit($id = 0,$input = []){

        if(!$input['u_id']){
            $this->set_error('未读取到当前操作管理员');return false;
        }
        if(!$input['sku_id']){
            $this->set_error('sku编号必填');return false;
        }
        if(!$input['add_sku_number']){
            $this->set_error('补货数必填');return false;
        }
        if(!$input['days']){
            $this->set_error('备货天数必填');return false;
        }

        return $this->update($id,$input);

    }

}