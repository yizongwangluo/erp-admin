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
            $order_goods_sql = 'insert into order_goods (product_id,sku_id,shop_id,shopify_o_id,quantity) values ';

            foreach($order_list as $k=>$value){
                $order[] = '("'.$value['id'].'",'.$shop_id.','.$value['total_price_usd'].',"'.$value['created_at'].'","'.$value['updated_at'].'",'.$value['total_weight'].',"'.$value['financial_status'].'","'.$time.'")';

                foreach($value['line_items'] as $i=>$item){
                    $order_goods[] = '("'.$item['product_id'].'","'.$item['sku'].'",'.$shop_id.',"'.$value['id'].'",'.$item['quantity'].')';
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
}