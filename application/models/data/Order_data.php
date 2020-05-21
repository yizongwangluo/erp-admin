<?php

/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */
set_time_limit ( 0 );
class Order_data extends \Application\Component\Common\IData{

    public function save($info = []){
        $order_info = $this->find(['shopify_o_id'=>$info['shopify_o_id']]);
        if(!$order_info){//不存在则添加
            $re = $this->store($info);
            if(!$re){
                log_message('order_add_err',date('Y-m-d H:i:s').'-----'.json_encode($info).'------',true);
            }else{
                return $re; //返回订单ID
            }
        }else{ //存在则修改  并记录日志
            unset($info['datetime']); //不修改时间
            unset($info['addtime']); //不修改时间
            $re = $this->update($order_info['id'],$info);
            if(!$re){
                log_message('order_edit_err',date('Y-m-d H:i:s').'-------id='.$order_info['id'].'-------'.json_encode($info).'------',true);
            }else{
                return $order_info['id']; //返回订单ID
            }
        }
        return false;
    }

    /**
     * 绑定运费
     * @param array $input
     * @return bool
     */
    public function add_import($input = []){

        //查询是否存在该运单号
        $info = $this->find(['tracking_number'=>$input['tracking_number']]);

        if(!$info){
            $this->set_error('未匹配到相关订单');return false;
        }
        if($info['freight']){
            $this->set_error('该订单运费已存在');return false;
        }

        //修改
        $ret = $this->update($info['id'],['freight'=>$input['freight']]);
        if(!$ret){
            $this->set_error('运费同步失败，请稍后再试');return false;
        }

        return true;
    }


    /**
     * 查询店铺总运费
     * @param array $input
     */
    public function get_freight_sum($input = []){

        if($input['datetime'] && $input['shop_id']){
            $sql = "select sum(freight) as freight from `order` where shop_id={$input['shop_id']} and datetime='{$input['datetime']}'";
            return $this->db->query ( $sql )->row_array ()['freight'];
        }
    }
}