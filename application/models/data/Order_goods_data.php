<?php

/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Order_goods_data extends \Application\Component\Common\IData{

    /**
     * 拆分sku公共方法
     * @param array $data
     * @return array
     */
    public function split_sku_comm($data = []){

        $data = array_column($data,null,'sku_id');

        $sku_list = [];

        foreach($data as $k=>$v){ //拆分捆绑sku
            $sku_tmp = explode('+',$v['sku_id']);

            foreach($sku_tmp as $i=>$t){

                $sku_id_tmp = explode('*',$t);

                $sku_list[$sku_id_tmp[0]]['sku_id'] =  $sku_id_tmp[0];
                $sku_list[$sku_id_tmp[0]]['quantity'] +=  max(1,$sku_id_tmp[1])*$v['quantity'];
                if(isset($v['countfreight'])){
                    $sku_list[$sku_id_tmp[0]]['countfreight'] +=  max(1,$sku_id_tmp[1])*$v['countfreight'];
                }
            }
        }
        return $sku_list;
    }



    /**
     * 重新绑定shop_ID
     */
    public function rebind_shop_id($shop_info){

        $sql = "update `order_goods` set shop_id={$shop_info['id']},user_id={$shop_info['user_id']}
                where shopify_o_id like '".$shop_info['code']."-%'";

        $this->db->query($sql);

        return $this->db->affected_rows() > 0;

    }

}