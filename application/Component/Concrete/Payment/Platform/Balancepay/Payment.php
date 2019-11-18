<?php
/**
 * 站内U币支付
 * User: xiongbaoshan
 * Date: 2016/4/13
 * Time: 18:43
 */

namespace Application\Component\Concrete\Payment\Platform\Balancepay;


use Application\Component\Concrete\Payment\EventHandler\OnBack;
use Application\Component\Concrete\Payment\EventHandler\OnNotify;
use Application\Component\Concrete\Payment\OrderFacade;
use Application\Component\Contract\Payment\BackEventHandler;
use Application\Component\Contract\Payment\NotifyEventHandler;

class Payment extends \MY_Model implements \Application\Component\Contract\Payment\Payment
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('data/user_data');
        $this->load->model('logic/user_logic');
    }

    public function submit($total_fee, $order_number, $order_name, $order_description)
    {


        $payment_facade=new OrderFacade();
        $payment_order=$payment_facade->get_order_by_order_number($order_number);

        //检查一下余额
        $user=$this->user_data->get_info($payment_order['payment_user_id']);
        if($user['money']<$total_fee){
            exit('account money not enough');
        }

        //不能为负数
        if($total_fee<0){
            exit('invalid total amount');
        }


        //付款金额大于0,才扣款,允许付款金额为0（当有优惠券减免时,支付金额可能为0）
        if($total_fee>0){
            if(!$this->user_logic->modify_money($payment_order['payment_user_id'],'-',$total_fee,$user['money'],$payment_order['origin_order_type'],$payment_order['order_summary'])){
                exit('pay fail');
            }
        }


        $data=array(
            'order_number'=>$order_number,
            'platform_order_number'=>'',
            $total_fee,
            time()
        );

        $notify_data =$data;
        $notify_data['buyer_account']=$payment_order['payment_user_id'];

        $this->notify(new OnNotify(),$notify_data);
        $this->back(new OnBack(),$data);

    }

    public function back(BackEventHandler $back_event_handler,$response=array())
    {
        call_user_func_array($back_event_handler,$response);
    }

    public function notify(NotifyEventHandler $notify_event_handle,$response=array())
    {

        call_user_func_array($notify_event_handle,$response);
    }

}