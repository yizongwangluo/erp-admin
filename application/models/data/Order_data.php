<?php

/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Order_data extends \Application\Component\Common\IData{

    public function add($info = []){
        $order_info = $this->find(['shopify_o_id'=>$info['shopify_o_id']]);
        if($order_info){//存在则修改
            $this->update($order_info['id'],$info);
        }else{ //不存在则 添加
            $this->store($info);
        }
    }
}