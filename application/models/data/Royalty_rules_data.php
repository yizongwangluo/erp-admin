<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Royalty_rules_data extends \Application\Component\Common\IData{

    /**
     * 查询
     * @param int $uid
     * @param array $where
     * @param string $order
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function get_lists($uid = 0,$where=[],$order = '',$page = 1,$limit=10){

        $sql = 'select {{}} from royalty_rules a ';
        if($uid!=1){ //超级管理员
            $sql .= ' INNER JOIN (select s_o_id from admin_org_temp where u_id=1 GROUP BY s_o_id) b on a.o_id=b.s_o_id';
        }

        $condition['total'] = 'COUNT(*) as total'; //统计
        $condition['info'] = 'a.*'; //列表

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
     * 添加
     * @param array $input
     * @return bool
     */
    public function add($input = []){

        if(empty($input['o_id'])){
            $this->set_error('请选择部门');return false;
        }
        if(!is_numeric($input['service_charge'])){
            $this->set_error('请填写手续费');return false;
        }
        if(!is_numeric($input['freight'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(!is_numeric($input['register_fee'])){
            $this->set_error('请填写挂号费');return false;
        }

        if($this->removal($input)){
            $this->set_error('该部门提成规则已存在，无法重复添加');return false;
        }

        unset($input['data_px']);
        unset($input['data_gmp']);
        $time = time();
        $input['datetime'] = $time;
        $input['edittime'] = $time;
        return $this->store($input);
    }


    /**
     * 修改
     * @param int $id
     * @param array $input
     * @return bool
     */
    public function edit($id = 0,$input= []){

        if(empty($input['o_id'])){
            $this->set_error('请选择部门');return false;
        }
        if(!is_numeric($input['service_charge'])){
            $this->set_error('请填写手续费');return false;
        }
        if(!is_numeric($input['freight'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(!is_numeric($input['register_fee'])){
            $this->set_error('请填写挂号费');return false;
        }

        if($this->removal(['id'=>$id,'o_id'=>$input['o_id']])){
            $this->set_error('该部门提成规则已存在，无法重复添加');return false;
        }

        unset($input['data_px']);
        unset($input['data_gmp']);

        $input['edittime'] = time();

        return $this->update($id,$input);

    }

    /**
     * 判断是否已存在该数据
     * @param array $input
     * @return bool
     */
    public function removal($input = array()){

        $data = [];

        $data['id !=']      = $input['id'] ? $input['id']:'';
        $data['o_id']    	 = $input['o_id'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
    }


}