<?php
/**
 * 自动发货 平台账号
 * @author：storm
 * Date: 2017/4/5 0005
 * Time: 10:37
 */

namespace Application\Component\Concrete\FirstRecharge\Channel;

use Application\Component\Common\ILogic;
use Application\Component\Traits\ErrorReporter;

class _autoaccount extends ILogic
{

    use ErrorReporter;

    function __construct()
    {
        parent::__construct();
        $this->load->model('data/order_data');
        $this->load->model('data/goods_data');
        $this->load->model('data/goods_account_data');
        $this->load->model('logic/goods_logic');
	    $this->load->model('logic/sms_logic');
    }

    /**
     * 获取账号信息
     * @param $goods
     * @param $status
     * @return mixed
     */
    private function get_account_info($goods, $status)
    {
        return $this->goods_account_data->get_one_account($goods['id'], $goods['saler_id'], $status);
    }

    /**
     * 锁定一个账号,订单提交时候触发
     * @param $goods
     */
    public function on_submit_order($goods, $order)
    {
    	if (!$goods['is_auto']){
		    return false;
	    }else{
		    //获取一个未使用的账号
		    $account = self::get_account_info($goods, '0');
		    if (!$account) {
			    $this->set_error('账号已被秒空,可能还有未付款订单,请稍后再试!');
			    return false;
		    }
		    $this->transaction->begin();
		    //为订单锁定一个库存
		    if (!$this->goods_account_data->lock_one_account($account['id'], $order['id'])){
			    $this->transaction->rollback();
			    $this->set_error('锁定发货库存失败');
			    return false;
		    } ;
		    //减少一个库存
		    if (!$this->goods_data->_counter_modify($goods['id'], 'store_number', '-',$order['goods_quantity'])){
			    $this->transaction->rollback();
			    $this->set_error('减少商品库存失败');
			    return false;
		    }
		    $this->transaction->commit();

		    return true;
	    }

    }

    /**
     * 取消订单时候触发
     */
    public function on_cancel_order($order_id)
    {
	    $order = $this->order_data->get_info($order_id);
	    if ($order['order_type'] = 'first_recharge') {
        //释放订单锁定
         $this->goods_account_data->unlock_one_account($order) ;
	    }
	    $this->goods_data->_counter_modify($order['goods_id'], 'store_number', '+',$order['goods_quantity']);
    }

    /**
     * 完成支付后
     * @param $order
     */
    public function on_payment($order)
    {
        $goods_info = $this->goods_data->get_info($order['goods_id']);
        if ($goods_info['is_auto']) {
            $goods = array(
                'id' => $order['goods_id'],
                'saler_id' => $order['seller_id']
            );
            $account = self::get_account_info($goods, $order['id']);
            $this->goods_account_data->use_one_account($account['id']);
            return $account;
        } else {
	        //商品减库存
	        $this->goods_logic->seller_count_modify($order['goods_id'], $order['goods_quantity']);
	        //通知卖家发货
	        $this->sms_logic->send_msg2_seller($order);
            return false;
        }
    }
}