<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Goods_sku_apply_data extends \Application\Component\Common\IData{

    /**
     * 修改
     * @param int $spu_id
     * @param array $input
     * @return bool
     */
    public function edit_status($spu_id = 0,$input = []){
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
}