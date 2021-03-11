<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Goods_sku_apply_data extends \Application\Component\Common\IData{


    public function add($input = []){
        $input['addtime'] = time();
        return $this->store($input);
    }

    /**
     * 修改
     * @param int $spu_id
     * @param array $input
     * @return bool
     */
    public function edit_status($spu_id = 0,$input = []){
        $input['edittime'] = time();
        return $this->update($spu_id,$input,'spu_id');
    }

    /**
     * 查询该spu下是否有sku未填写编码code
     * @param int $spu_id
     * @return bool
     */
    public function get_no_code($spu_id = 0){

        $sql = "SELECT count(*) as count from goods_sku_apply where spu_id=".$spu_id." and code='' ";
        $query = $this->db->query($sql);
        $info = $query->row_array();
        return $info['count']?true:false;
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

    /**
     * 别名是否是唯一的
     * @param array $input
     * @param bool|false $a
     * @return bool
     */
    public function get_only($input = [],$a = false){

        $alias = explode(',',$input['alias']);

        $where = [];

        if($alias){
            foreach($alias as $v){
                $where[] = "FIND_IN_SET('{$v}',alias)";
                $where[] = "code='{$v}'";
            }
        }

        if($input['code']){
            $where[] = "FIND_IN_SET('{$input['code']}',alias)";
//            $where[] = "code='{$input['code']}'";
        }

        $where = implode(' or ',$where);

        if($input['code'] && $a){
            $where = "(".$where.") and code!= '{$input['code']}'";
        }elseif($input['id']){
            $where = "(".$where.") and id!= '{$input['id']}'";
        }

        $sql = 'select COUNT(*) as count from goods_sku_apply where '.$where;
        $query = $this->db->query($sql);
        $info = $query->row_array();
        return $info['count']?false:true;
    }

}