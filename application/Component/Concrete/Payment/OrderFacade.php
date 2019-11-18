<?php
/**
 * 付款订单
 * User: xiongbaoshan
 * Date: 2016/4/6
 * Time: 18:06
 */

namespace Application\Component\Concrete\Payment;

use Application\Component\Contract\ErrorReporter;

class OrderFacade implements ErrorReporter
{
    use \Application\Component\Traits\ErrorReporter;

    public function __construct()
    {
        $this->db=get_instance()->load->database('',true);
    }

    /**
     * 创建支付订单
     * @param string $origin_order_type 原始订单类型
     * @param int $origin_order_id  原始订单ID
     * @param string $origin_order_summary  原始订单摘要
     * @param int $payment_user_id  付款用户ID
     * @param string $payment_platform 付款平台
     * @param double $payment_amount   付款金额
     * @return int
     */
    public function create_order($origin_order_type,$origin_order_id,$origin_order_summary,$payment_user_id,$payment_platform,$payment_amount){

        if($exists_order=$this->get_order_by_origin_order($origin_order_type,$origin_order_id)){

            if($exists_order['status']!='PAYMENT_WAITING'){
                $this->set_error('非法支付操作(STATUS_INVALID)');
                return false;
            }

            if($exists_order['payment_platform']!=$payment_platform){
                $this->db->update(
                    'payment_order',
                    array(
                        'payment_platform'=>$payment_platform,
                    ),
                    array('order_id'=>$exists_order['order_id'])
                );
                if($this->db->affected_rows()<=0){
                    $this->set_error('付款订单更新失败');
                    return false;
                }
            }

            //++++++++++++++++++BlueIdea 2018.04.26 增加++++++++++++++++++//
            //TODO:如果支付方式是汇付宝，当重新触发充值操作时更新订单
            if ($payment_platform == 'heepay') {
                $this->db->update(
                    'payment_order',
                    array(
                        'order_number' => self::create_number($origin_order_type, $origin_order_id, $payment_user_id),
                    ),
                    array('order_id' => $exists_order['order_id'])
                );
                if($this->db->affected_rows()<=0){
                    $this->set_error('付款订单更新失败');
                    return false;
                }
            }
            //++++++++++++++++++BlueIdea 2018.04.26 增加++++++++++++++++++//

            return $exists_order['order_id'];

        }else{

            $this->db->insert( 'payment_order',array(
                'order_number'=>self::create_number($origin_order_type,$origin_order_id,$payment_user_id),
                'order_summary'=>$origin_order_summary,
                'origin_order_type'=>$origin_order_type,
                'origin_order_id'=>$origin_order_id,
                'payment_user_id'=>$payment_user_id,
                'payment_amount'=>(float)$payment_amount,
                'payment_platform'=>$payment_platform,
                'status'=>'PAYMENT_WAITING',
                'create_time'=>time(),
            ));

            $order_id=$this->db->insert_id();
            if(!$order_id){
                $this->set_error('付款订单创建失败');
                return false;
            }

            return $order_id;
        }
    }

    /**
     * 提交到收银台
     * @param $order_id
     */
    public function submit_to_cashier($order_id){

        header('Location:/payment_gateway/redirect?order_id='.$order_id, TRUE, 302);
        exit;
    }

    /**
     * 获取支付订单信息
     * @param $order_id
     * @return array
     */
    public function get_order($order_id){
        return (array)$this->db
            ->where(array('order_id'=>$order_id))
            ->get( 'payment_order' )
            ->row_array();
    }

    /**
     * 获取支付订单信息
     * @param $origin_order_type
     * @param $origin_order_id
     * @return array
     */
    public function get_order_by_origin_order($origin_order_type,$origin_order_id){
        return (array)$this->db
            ->where(array(
                'origin_order_type'=>$origin_order_type,
                'origin_order_id'=>$origin_order_id
            ))
            ->get( 'payment_order' )
            ->row_array();
    }

    /**
     * 获取支付订单信息
     * @param $order_number
     * @return array
     */
    public function get_order_by_order_number($order_number){
        return (array)$this->db
            ->where(array('order_number'=>$order_number))
            ->get( 'payment_order' )
            ->row_array();
    }

    /**
     * 支付成功
     * @param $order_id
     * @param int $success_time
     */
    public function payment_success($order_id,$platform_order_number,$success_time=0,$buyer_account=''){
        $this->db->update(
            'payment_order',
            array(
                'status'=>'PAYMENT_SUCCESS',
                'platform_order_number'=>$platform_order_number,
                'success_time'=>$success_time?$success_time:time(),
                'buyer_account'=>$buyer_account,
            ),
            array('order_id'=>$order_id)
        );
    }
    /**
     * 支付完成
     * @param $order_id
     * @param int $complete_time
     */
    public function payment_complete($order_id,$complete_time=0){
        $this->db->update(
            'payment_order',
            array(
                'status'=>'PAYMENT_COMPLETE',
                'complete_time'=>$complete_time?$complete_time:time(),
            ),
            array('order_id'=>$order_id)
        );
    }

    /**
     * 判断是不是成功状态
     * @param $order_status
     * @return bool
     */
    public function payment_succeeded($order_status){
        return $order_status !='PAYMENT_WAITING';
    }

    public function payment_closed($order_status){
	    return $order_status == 'PAYMENT_CLOSED';
    }

    /**
     * 当订单取消时调用
     * @param $origin_order_type
     * @param $origin_order_id
     * @return bool
     */
    public function order_cancel($origin_order_type, $origin_order_id,$trade_no='')
    {
        $payment_order=$this->get_order_by_origin_order($origin_order_type,$origin_order_id);
        if($payment_order){
            $this->db->update(
                'payment_order',
                array(
                    'status'=>'PAYMENT_CLOSED',
	                'platform_order_number' =>$trade_no,
	                'complete_time'=> time()
                ),
                array('order_id'=>$payment_order['order_id'])
            );
        }

        return true;
    }

    /**
     * 创建订单号
     * @param $origin_order_type
     * @param $origin_order_id
     * @param $payment_user_id
     * @return string
     */
    protected static function create_number($origin_order_type,$origin_order_id,$payment_user_id){
        return 'TRADE'.date("YmdHis").rand(100000000000,999999999999);
    }

}



/**
 * 更好的单词首字母大写转换函数
 * @param string $str
 * @return string
 */
function best_ucwords($str=''){
    return str_replace(' ','',ucwords(str_replace('_',' ',$str)));
}