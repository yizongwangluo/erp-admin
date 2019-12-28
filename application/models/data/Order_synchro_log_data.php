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
     * @param string $url
     * @param string $min_time
     * @param string $mix_time
     * @param int $page
     * @return int
     */
    public function add_log($shop_id = 0,$url = '',$min_time = '',$mix_time = '',$page = 1,$datetime = ''){

        return $this->store(['shop_id'=>$shop_id,'link'=>$url,'min_time'=>$min_time,'mix_time'=>$mix_time,'page'=>$page,'datetime'=>time()]);

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