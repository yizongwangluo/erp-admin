<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Advert_data extends \Application\Component\Common\IData{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function get_list($u_id = 0,$where = [],$page = 1,$limit = 15){

        if(empty($u_id)){
            $this->set_error('系统繁忙，请稍后重试！');return false;
        }

        if($u_id==1){ //admin账号查询时
            $table_b = 'left join admin b on a.u_id=b.id ';
        }else{
            $table_b = ' INNER JOIN (select s_u_id,s_user_name as user_name from admin_org_temp where u_id='.$u_id.' GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }

        $condition['total'] = 'COUNT(*) as total';
        $condition['info'] = 'a.*,b.user_name';

        $sql = 'select {{}} from advert a '.$table_b;

        if(is_numeric($where['status'])){
            $sql_where[] = ' a.status='.$where['status'];
        }
        if($where['type']){
            $sql_where[]= ' a.type='.$where['type'];
        }
        if($where['advert_id']){
            $sql_where[]= ' a.advert_id= "'.$where['advert_id'].'" ';
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
     * 保存数据
     * @param array $data
     * @return bool|int
     */
    public function save($data = []){

        $data = array_filterempty($data);

        if(!$data['type'] || !$data['advert_id'] || !$data['recharge_amount']){
            $this->set_error('必填项未填写完整！');return false;
        }

        $time = time();

        if($data['id']){ //修改
            $id = $data['id'];
            unset($data['id']);
            $data['edittime'] = $time;
            return $this->update($id,$data);
        }else{
            $data['apply_time'] = date('Y-m-d');
            $data['addtime'] = $time;
            $data['edittime'] = $time;

            return $this->store($data);
        }
    }




}