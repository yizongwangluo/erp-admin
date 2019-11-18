<?php

/**
 * 微信支付（使用官方支付平台）
 * User: BlueIdea
 * Date: 2018/5/22
 * Time: 13:44
 */

namespace Application\Component\Concrete\Payment\Platform\Wxpay;

use Application\Component\Contract\Payment\BackEventHandler;
use Application\Component\Contract\Payment\NotifyEventHandler;

final class Payment extends \MY_Model implements \Application\Component\Contract\Payment\Payment {

    protected $app = null;

    /**
     * 构造方法
     * @return mixed|void
     */

    public function __construct () {
        $ci = & get_instance ();
        $ci->load->config ( 'wechat' );
        $options = $ci->config->item ( 'wechat' );
        $this->app = new \EasyWeChat\Foundation\Application ( $options );
    }

    /**
     * 下单提交
     * @param \Application\Component\Contract\Payment\付款金额 $total_fee
     * @param \Application\Component\Contract\Payment\订单号码 $order_number
     * @param \Application\Component\Contract\Payment\订单名称 $order_name
     * @param \Application\Component\Contract\Payment\订单描述信息 $order_description
     * @return string|void
     */

    public function submit ( $total_fee, $order_number, $order_name, $order_description ) {
        $attributes = [
            'trade_type'       => IS_WAP ? 'MWEB' : 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => $order_name,
            'detail'           => $order_name,
            'out_trade_no'     => $order_number,
            'total_fee'        => $total_fee * 100, // 单位：分
            'time_start'       => date("YmdHis"),
            'time_expire'      => date("YmdHis", time() + 300),
            'notify_url'       => base_url () . 'payment_gateway/notify/wxpay', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'scene_info'       => IS_WAP ?
                '{"h5_info": {"type":"Wap","wap_url": "https://m.jiaoyitu.com","wap_name": "交易兔充值"}}' : '',
        ];

        $order = new \EasyWeChat\Payment\Order($attributes);
        $result = $this->app->payment->prepare($order);

        if ( $result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS' ) {
            $url = IS_WAP ? $result->mweb_url : $result->code_url;
            if (IS_WAP) {
                echo "<script>window.parent.location.href = '$url';</script>";
            } else {
                include( __DIR__ . '/qrcode_display.php' );
            }
        }
    }

    /**
     * 支付同步通知，支付成功后跳转到页面（方法保留）
     * @param BackEventHandler $back_event_handler
     * @return mixed|void
     */

    public function back ( BackEventHandler $back_event_handler ) {
        $order_number = $_GET['out_trade_no'];
        //事件回调
        call_user_func_array( $back_event_handler, array( $order_number, '', '', '', '', '') );
    }

    /**
     * 支付异步通知
     * @param NotifyEventHandler $notify_event_handler
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */

    public function notify ( NotifyEventHandler $notify_event_handler ) {
        $app = $this->app;

        $response = $app->payment->handleNotify ( function( $notify, $successful )
            use ( $app, $notify_event_handler ) {

            //查询订单，判断订单真实性
            if( ! $app->payment->queryByTransactionId( $notify->transaction_id ) ) {
                return false;
            }

            if ($successful) {
                //商户订单号
                $order_number          = $notify->out_trade_no;
                //微信支付订单号
                $platform_order_number = $notify->transaction_id;
                //交易金额
                $trading_amount        = bcdiv($notify->total_fee, 100, 2);
                //交易时间
                $trading_time          = $notify->time_end;
                //买家支付账号
                $buyer_account         = '';

                //事件回调
                call_user_func_array( $notify_event_handler,
                    array( $order_number, $platform_order_number, $trading_amount, $trading_time, $buyer_account ) );
            }

            return true; // 或者错误消息
        });

        $response->send();
    }

}