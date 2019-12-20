<?php

/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Order_synchro_log_data extends \Application\Component\Common\IData{

    /**
     * 添加日志
     * @param int $shop_id
     * @param string $shop_url
     * @param string $url
     * @param int $page
     * @return int
     */
    public function add_log($shop_id = 0,$shop_url = '',$url = '',$page = 1){

        return $this->store(['shop_id'=>$shop_id,'shop_url'=>$shop_url,'link'=>$url,'page'=>$page]);

    }


    /**
     * 修改订单同步状态
     * @param int $id
     * @param int $type
     * @return bool
     */
    public function  edit_log($id = 0,$type = 1){
        return $this->update($id,['type'=>$type]);
    }
}