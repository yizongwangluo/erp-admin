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

        return $info['datetime'].'|'.$info['shop_id'];
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


    /**
     * 批量修改物流单号
     * @param array $data
     */
    public function edit_tracking_number($data = []){

        $this->db->update_batch('order', $data, 'shopify_o_id');
    }

    /**
     * 获取需要更新物流单号的的订单ID
     */
    public function get_tracking_list($limit = 100){

        //获取2天前  的7天内的数据
//        $date_start = date("Y-m-d",strtotime("-9 day"));
        $date_end = date("Y-m-d",strtotime("-1 day"));
//        $date_end = date("Y-m-d");
//        $data = ['datetime >='=>$date_start,'datetime <='=>$date_end,'tarcking_number_req <'=>5];

        $data = ['datetime <='=>$date_end,'datetime >='=>'2020-09-07','tracking_number_req <'=>5,'tracking_type'=>0];

        //请求次数小于5次
         $list = $this->db->select('id,shopify_o_id')
                    ->from('order')
                    ->where($data)
                    ->order_by('datetime')
                     ->limit($limit)
                     ->get()->result_array();

        return $list;
    }

    /**
     * 同步次数加一
     * @param array $id
     * @return mixed
     */
    public function tracking_number_req_add_one($id = array()){
        $this->db->where_in('id',$id);
        $this->db->set('tracking_number_req','tracking_number_req+1',FALSE);
        $result =  $this->db->update('order');
        return $result;
    }


    /**
     * 重新绑定shop_ID
     */
    public function rebind_shop_id($shop_info){

        $sql = "update `order` set shop_id={$shop_info['id']},user_id={$shop_info['user_id']}
                where shopify_o_id like '".$shop_info['code']."-%'";

        $this->db->query($sql);

        return $this->db->affected_rows() > 0;

    }

}