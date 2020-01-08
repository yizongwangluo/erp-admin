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

        if($where['name'] || $uid==1){ //当输入关键词的时候
            $table_b = 'left join admin b on a.u_id=b.id ';
        }else{
            $table_b = ' INNER JOIN (select s_u_id,s_user_name as user_name from admin_org_temp where u_id='.$uid.' GROUP BY s_u_id) b on a.u_id=b.s_u_id ';
        }

        $condition['total'] = 'COUNT(*) as total';
        $condition['info'] = 'a.*,b.user_name';

        $sql = 'select {{}} from goods a '.$table_b;

        if(is_numeric($where['status'])){
            $sql_where[] = ' a.status='.$where['status'];
        }
        if(is_numeric($where['category_id'])){
            $sql_where[] = ' a.category_id='.$where['category_id'];
        }
        if($where['name']){
            $sql_where[]=" a.name = '".$where['name']."'";
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
                $sku_list = $this->db->query('select code,norms,price,weight,status,code from goods_sku where spu_id= '.$v['id'])->result_array();
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

        //$input = array_filter($input);

        $input['status'] = $input['status'] || is_numeric($input['status'])?$input['status'] : 0; //修改审核状态为未审核

        $arr['edittime'] = time();

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

        //时间
        $time = time();
        $arr['addtime'] = $time;
        $arr['edittime'] = $time;

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
     * 同步
     * @param array $apply_info
     * @return bool
     */
    public function synchronization($apply_info = []){

        $info = $this->find(['code'=>$apply_info['code']]);
        //过滤参数
        unset($apply_info['id']);
        unset($apply_info['sales_volume']);
        unset($apply_info['u_id']);
        unset($apply_info['addtime']);
        unset($apply_info['edittime']);
        unset($apply_info['status']);
        unset($apply_info['type']);
        //过滤参数end

        $time = time();
        $apply_info['edittime'] = $time;
        if($info){ //修改
           /* if(!$this->update($info['id'],$apply_info)){
                $this->set_error('(修改)同步失败，请稍后重试');return false;
            }*/
        }else{ //新增
            $apply_info['addtime'] = $time;
            $info['id'] = $this->store($apply_info);
            if(!$info['id']){
                $this->set_error('(新增)同步失败，请稍后重试');return false;
            }
        }
        return $info['id'];
    }
}