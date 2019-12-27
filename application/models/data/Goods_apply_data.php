<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Goods_apply_data extends \Application\Component\Common\IData{


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

        if(empty($arr['img'])){
            $this->set_error('请上传产品图片');return false;
        }

        if(empty($arr['benchmarking'])){
            $this->set_error('请填写视频链接');return false;
        }


        $arr = array_filter($arr);
        $id = $this->store($arr);

        if(!$id){
            $this->set_error('添加失败，请稍后重新添加');return false;
        }

        if($id && count($sku_list)){ //添加spu成功，添加sku
            $sql = 'INSERT INTO goods_sku_apply (spu_id,code,norms,img,price,size,weight,cycle,information,remarks,u_id,type,is_real,status)  VALUES ';
            $sql_val = [];
            foreach($sku_list as $k=>$value){
                $code = '';
                if($value['is_real']){ //测试sku
                    $code = date('YmdHis').'_'.$id.'_'.$k;
                }
                $sql_val[] = "({$id},'{$code}','{$value['norms']}','{$value['img']}',{$value['price']},'{$value['size']}',{$value['weight']},{$value['cycle']},'{$value['information']}','{$value['remarks']}',{$u_id},{$value['type']},{$value['is_real']},{$status})";
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

        if($where['keyword'] || $uid==1){ //当输入关键词的时候
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

            foreach($info as $k=>$v){
                $sku_list = $this->db->query('select code,norms,price,weight,status,code from goods_sku_apply where spu_id= '.$v['id'])->result_array();
                $info[$k]['sku_code'] = implode('<br/>',array_column($sku_list,'code'));
                $info[$k]['norms'] = implode('<br/>',array_column($sku_list,'norms'));
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
}