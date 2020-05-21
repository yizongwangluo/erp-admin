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
    public function add_log($shop_id = 0,$url = '',$min_time = '',$mix_time = '',$page = 1,$order_cout = 0){

        return $this->store(['shop_id'=>$shop_id,'link'=>$url,'min_time'=>$min_time,'mix_time'=>$mix_time,'page'=>$page,'datetime'=>date('Y-m-d H:i:s'),'order_cout'=>$order_cout]);

    }


    /**
     * 修改订单同步状态
     * @param int $id
     * @param int $type
     * @param int $order_cout
     * @param int $success_cout
     * @return bool
     */
    public function  edit_log($id = 0,$type = 1,$order_cout = 0,$success_cout = 0,$remarks = ''){
        return $this->update($id,['type'=>$type,'order_cout'=>$order_cout,'success_cout'=>$success_cout,'remarks'=>$remarks]);
    }
}