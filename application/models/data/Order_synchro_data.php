<?php

/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Order_synchro_data extends \Application\Component\Common\IData{

    public function get_shop_one(){

        $sql = 'select * from order_synchro ORDER BY new_time limit 1';
        $info = $this->db->query ( $sql )->row_array ();

        return $info;
    }


    public function edit_shop_time($id = 0,$time = ''){

        return $this->update($id,['new_time'=>$time]);

    }
}