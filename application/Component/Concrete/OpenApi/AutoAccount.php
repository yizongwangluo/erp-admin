<?php
/**
 * 首充号自动发货
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/8/1 0001
 * Time: 11:51
 */

namespace Application\Component\Concrete\OpenApi;

class AutoAccount
{

	private $order;
	private $notify;
	private $notifyKey;

	public function __construct ( $order )
	{
		$this->order = $order;
		$this->notify = [1=>'',2=>'',3=>'http://h5.yuu1.com/Mobile/PayNotify/notify'];
        $this->notifyKey = 'JiaoYiTu Generate privilege account';
    }

	/**
	 * 获取账号信息
	 * @param $goods
	 * @param $status
	 * @return mixed
	 */
	private function get_account_info ( $goods, $status )
	{
		return \model ( 'data/goods_account_data' )->get_one_account ( $goods['id'], $goods['saler_id'], $status );
	}

	public function fire ( $info, \Closure $callback )
	{
		//获取一个未使用的账号
		$account = $this->get_account_info ( $info, '0' );
		if ( !empty($account) ) {
			$order = $this->order;

            /**
             *  通过订单seller_id获取商铺是否为直营店铺 如果是优易网的则通知发货
             */
            $store = \model ( 'data/Store_data' )->get_info_by_store_fee ($order['seller_id']);
            if($store['shop_type'] == 1 && $store['platform'] == 3){
                if(!$this->noticeAutotrophy($order,$store['platform'],$account['account'])){
                    log_message ( 'notice_platform', $order['id'].'--'.$account['account'], true );
                    return false;
                }
            }

			try {
				\model ( 'logic/order_for_first_recharge_logic' )->do_delivery ( $order['id'], $account['account'], $account['password'], $order, $account );
				\model ( 'logic/wechat_logic' )->on_autogood_notify ( $order );
			} catch ( Exception $e ) {
				log_message ( 'do_delivery_error_msg', $e->getMessage (), true );
				$callback();
				return false;
			}
		}
	}

    /**
     * 直营店铺通知
     * @param $order
     * @param $platform
     * @param $account
     * @return bool
     */

	private function noticeAutotrophy($order, $platform, $account){

        $data['money']     =  $order['original_price']*$order['goods_quantity']; //原价
        $data['pay_money'] =  $order['pay_amount'];    //付款金额
        $data['username']  = $account;
        $data['timestamp'] = time();
        $data['orderid']   = $order['order_number'];
        $data['token']     = $this->createToken($data);

        $result = curl_post($this->notify[$platform],$data);

        if($result === 'SUCCESS'){
            return true;
        }

        log_message ( 'notice_platform', http_build_query($data).'---resule---'.$result, true );

        return false;
    }

    /**
     * 生成请求的token
     * @param array $data
     * @return string
     */

    private function createToken(array $data){

        unset($data['orderid']);

        ksort($data);

	    return md5(http_build_query($data).$this->notifyKey);
    }
}