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
     * @param string $shop_url
     * @param string $link
     * @return int
     */
    public function add_log($shop_url = '',$link = ''){

        return $this->store(['shop_url'=>$shop_url,'link'=>$link],true);

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