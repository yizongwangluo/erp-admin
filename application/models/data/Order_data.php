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
            $re = $this->update($info);
            if(!$re){
                log_message('order_edit_err',date('Y-m-d H:i:s').'-------id='.$order_info['id'].'-------'.json_encode($info).'------',true);
            }else{
                return $order_info['id']; //返回订单ID
            }
        }
        return false;
    }


    /**
     * 添加
     * @param int $shop_id
     * @param string $time
     * @param array $order_list
     * @return bool
     */
    public function add_order($shop_id = 0,$time = '',$order_list = []){
        try {
            $this->db->trans_strict(FALSE);
            $this->db->trans_begin();

            $order = [];
            $order_goods = [];

            $order_sql = 'insert into `order` (shopify_o_id,shop_id,total_price_usd,created_at,updated_at,total_weight,financial_status,datetime) values ';
            $order_goods_sql = 'insert into order_goods (product_id,sku_id,shop_id,shopify_o_id,quantity,datetime) values ';

            foreach($order_list as $k=>$value){
                $date = substr($value['updated_at'],0,strpos($value['updated_at'], 'T'));
                $order[] = '("'.$value['id'].'",'.$shop_id.','.$value['total_price_usd'].',"'.$value['created_at'].'","'.$value['updated_at'].'",'.$value['total_weight'].',"'.$value['financial_status'].'","'.$date.'")';

                foreach($value['line_items'] as $i=>$item){
                    $order_goods[] = '("'.$item['product_id'].'","'.$item['sku'].'",'.$shop_id.',"'.$value['id'].'",'.$item['quantity'].',"'.$date.'")';
                }
            }

            $order_sql .= implode(',',$order);
            $query = $this->db->query($order_sql);
            if($this->db->affected_rows()<=0){ //添加失败
                log_message('add_order','order_sql-'.$time.':'.$order_sql,true);
                return false;
            }

            $order_goods_sql .= implode(',',$order_goods);
            $query = $this->db->query($order_goods_sql);
            if($this->db->affected_rows()<=0){ //添加失败
                log_message('add_order','order_goods_sql-'.$time.':'.$order_goods_sql,true);
                return false;
            }

            $this->db->trans_complete();
            return true;

        }catch(PDOException $e) {
            $this->db->trans_rollback();
            log_message('add_order','add_order-'.$time.':'.$e->getMessage(),true);
//            exit($e->getMessage());
        }
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