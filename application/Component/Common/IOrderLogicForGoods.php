<?php
/**
 * 商品订单抽象接口（todo:如果系统增加其他交易类型,则实现该接口就行了）
 * User: xiongbaoshan
 * Date: 2016/8/5
 * Time: 19:09
 */

namespace Application\Component\Common;
use Application\Component\Concrete\DiscountActivity\Factory as ActivityFactory;
use Application\Component\Concrete\Event\Dispatcher;
use Application\Component\Concrete\Event\Payload;


abstract class IOrderLogicForGoods extends ILogic implements IOrderLogic
{
     public function __construct()
     {
         parent::__construct();
         $this->load->model('data/discount_activity_data');
         $this->load->model('data/user_coupon_data');
         $this->load->model('data/order_data');
         $this->load->model('data/goods_data');
     }

     /**
      * 订单创建之前执行（todo:必需返回true才能继续执行）
      * @param $user_id 当前用户
      * @param $response 接收到的数据
      * @param $goods 商品信息
      * @return bool
      */
    abstract public function before_order_create($user_id,$response, $goods);

     /**
      * 统计订单金额
      * @param $user_id
      * @param $response
      * @param $goods
      * @return mixed
      */

    public function sum_order_amount($user_id, $response, $goods){
        return $goods['price'] * $response['quantity'];
    }

     /**
      * 打折接口
      * @param $user_id
      * @param $response
      * @param $goods
      * @return array
      */

     public function discount($user_id, $response, $goods) {
         $order_amount = $this->sum_order_amount($user_id, $response, $goods);  //计算金额
         $discount_amount = 0;
         $discount_detail = [];
         $now = time();

         //打折活动减免
         $discount_activity_list=$this->discount_activity_data->lists("start_time < {$now} and end_time > {$now}");
         foreach($discount_activity_list as $item){
             $activity=ActivityFactory::get_instance($item['alias']);
             $reduce_money=$activity->discount($user_id,$goods['id'],$response['quantity'],$order_amount);
             if(!$reduce_money)continue;
             if($order_amount-$discount_amount-$reduce_money<0)continue;
             $discount_detail[]=[
                 'type'=>'activity',
                 'flag'=>$item['id'],
                 'money'=>$reduce_money,
                 'note'=>'优惠活动：'.$item['name'],
             ];
             $discount_amount+=$reduce_money;
         }


         //抵扣一个代金券
         if($user_id){
             $coupon_list=$this->user_coupon_data->lists("user_id={$user_id} and is_used=0 and end_time>{$now}",['money','desc']);
             foreach($coupon_list as $item){
                 if($item['limits'] && !in_array($goods['type'],explode('|',$item['limits'])))continue;
                 if($order_amount<$item['condition'])continue;
                 $reduce_money=min($order_amount-$discount_amount,$item['money']);
                 $discount_detail[]=[
                     'type'=>'coupon',
                     'flag'=>$item['id'],
                     'money'=>$reduce_money,
                     'note'=>'用代金券：'.$reduce_money.'元',
                 ];
                 $discount_amount+=$reduce_money;
                 break;
             }
         }

         $pay_amount=$order_amount-$discount_amount;
         // 折扣优惠 原价 * 数量 - 支付金额 = 优惠
         $discount_amount+= $goods["original_price"]*$response['quantity']-$pay_amount;
         return [
             'order_amount'=>$order_amount,//订单金额
             'pay_amount'=>$pay_amount,//应付金额
             'discount_amount'=>$discount_amount,//打折金额
             'discount_detail'=>$discount_detail,//打折明细
         ];
     }

     /**
      * 根据商品创建订单内容
      * @param $user_id 用户ID
      * @param array $response 接收数据
      * @param array $goods 商品信息
      * @return string
      */
     public function create_order_content($user_id,array $response,array $goods){
         return $goods['title'];
     }

     /**
      * 订单基础数据保存之后执行（todo:必需返回true才能继续执行）
      * @param $response 接收到的数据
      * @param $goods 商品信息
      * @param $order 订单数据
      * @return bool
      */
     abstract public function after_order_create($response, $goods, $order);

     /**
      * 订单支付成功通知
      * @param $order_id
      */
     public function on_payment_notify($order_id)
     {
         $order=$this->order_data->get_info($order_id);
     }

     /**
      * 订单自动发货接口
      * @param $order
      * @return bool
      */
     abstract public function delivery ($order);

     /**
      * 接收自动发货通知
      * @param $response
      * @return bool
      */
     abstract public function delivery_notify($response);

     /**
      * 手动发货接口
      * @param $response
      * @return bool
      */
     abstract public function manual_delivery($response);

     /**
      * 当发货成功时回调（todo:不管自动还是手动发货成功后,请务必回调该方法）
      * @param $order
      */
     public function on_delivery_success($order){
         $event=new Payload('delivery_success',[
             'order_id'=>$order['id']
         ]);
         return Dispatcher::get_instance()->fire($event);
     }

}