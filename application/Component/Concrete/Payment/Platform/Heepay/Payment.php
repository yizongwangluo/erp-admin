<?php

/**
 * 汇付宝微信支付
 * User: blueidea
 * Date: 2018/4/19
 * Time: 12:06
 */

namespace Application\Component\Concrete\Payment\Platform\Heepay;

use Application\Component\Contract\Payment\BackEventHandler;
use Application\Component\Contract\Payment\CancelEventHandler;
use Application\Component\Contract\Payment\NotifyEventHandler;

class Payment implements \Application\Component\Contract\Payment\Payment {

    public function __construct() {
        //检测是否手机端
        if(!defined('IS_WAP')){
            define('IS_WAP', false);
        }
        $this->config = $this->load('heepay.config.php');
    }

    public function submit($total_fee, $order_number, $order_name, $order_description = '') {
        $orderReq['version']         = 1;   //当前接口版本号1，话费通卡版本号为2

        IS_WAP && $orderReq['is_phone'] = 1;
        IS_WAP && $orderReq['is_frame'] = 0;

        $orderReq['pay_type']        = 30; //30微信支付
        $orderReq['agent_id']        = IS_WAP ? $this->config['agent_id'] : $this->config['agent_id_pc'];
        $orderReq['agent_bill_id']   = $order_number;
        $orderReq['pay_amt']         = $total_fee;
        $orderReq['notify_url']      = $this->config['notify_url'];   //支付结果通知地址
        $orderReq['return_url']      = $this->config['return_url'];
        $orderReq['user_ip']         = $this->getip();
        $orderReq['agent_bill_time'] = date('YmdHis', time());
        $orderReq['goods_name']      = iconv('utf-8', 'gbk', '微信支付');
        $orderReq['goods_num']       = '';
        $orderReq['remark']          = '';
        $orderReq['goods_note']      = '';
        $orderReq['timestamp']       = '';

        IS_WAP && $orderReq['meta_option']     =  base64_encode('{"s":"WAP","n":"jiaoyitu","id":"https://m.jiaoyitu.com"}');

        $sign_key                    = IS_WAP ? $this->config['sign_key'] : $this->config['sign_key_pc']; //签名密钥，需要商户使用为自己的真实KEY

        $orderReq['sign']            = $this->createSign(
            $orderReq['version'],
            $orderReq['agent_id'],
            $orderReq['agent_bill_id'],
            $orderReq['agent_bill_time'],
            $orderReq['pay_type'],
            $orderReq['pay_amt'],
            $orderReq['notify_url'],
            $orderReq['return_url'],
            $orderReq['user_ip'],
            $sign_key
        );

        $request_url = $this->config['pay_url'] . '?' . http_build_query($orderReq);

        log_message ('heepay_pay_success_log', $request_url, true);

        echo "<script>window.parent.location.href = '$request_url';</script>";
    }

    /**
     * 汇付宝异步回调通知
     * @param NotifyEventHandler $notify_event_handler
     */

    public function notify(NotifyEventHandler $notify_event_handler) {
        $return_sign = $_GET['sign'];
        $sign        = $this->returnNotifySign($this->config);
        file_put_contents('/home/www/www.jiaoyitu.com/application/notifylogs', $return_sign . '&&&' . $sign . "\n", FILE_APPEND);
        log_message ('heepay_notify_success_log', 'in_01' . $return_sign . '&&&' . $sign, true);
        //比较签名密钥结果是否一致，一致则保证了数据的一致性
        if ( $sign == $return_sign && intval($_GET['result'])  == 1 ) {
            log_message ('heepay_notify_success_log', 'in_02' . $return_sign . '&&&' . $sign, true);
            //商户订单号
            $order_number          = $_GET['agent_bill_id'];
            //汇付宝交易号
            $platform_order_number = $_GET['jnet_bill_no'];
            //交易金额
            $trading_amount        = $_GET['pay_amt'];
            //交易时间
            $trading_time = '';
            //买家支付账号
            $buyer_account = '';
            $extends = [
                'agent_id' => $_GET['agent_id']
            ];
            //事件回调
            call_user_func_array($notify_event_handler, array($order_number, $platform_order_number, $trading_amount, $trading_time, $buyer_account, $extends));
            log_message ('heepay_notify_success_log', 'order_number:' . $order_number . '  platform_order_number:' . $platform_order_number . 'trading_amount:' . $trading_amount, true);
            echo "success";
        } else {
            log_message ('heepay_notify_fail_log', 'error', true);
            echo "fail";
        }
    }

    public function back(BackEventHandler $back_event_handler) {
        log_message ('heepay_back_success_log', 'ok', true);
        $return_sign = $_GET['sign'];
        $sign        = $this->returnNotifySign($this->config);
        log_message ('heepay_back_success_log', 'in_01' . $return_sign . '&&&' . $sign, true);
        //比较签名密钥结果是否一致，一致则保证了数据的一致性
        if ( $sign == $return_sign && intval($_GET['result'])  == 1 ) {
            log_message ('heepay_back_success_log', 'in_02' . $return_sign . '&&&' . $sign, true);
            //商户订单号
            $order_number          = $_GET['agent_bill_id'];
            //汇付宝交易号
            $platform_order_number = $_GET['jnet_bill_no'];
            //交易金额
            $trading_amount        = $_GET['pay_amt'];
            //交易时间
            $trading_time = '';
            //买家支付账号
            $buyer_account = '';
            $extends = [
                'agent_id' => $_GET['agent_id']
            ];
            //事件回调
            call_user_func_array($back_event_handler, array($order_number, $platform_order_number, $trading_amount, $trading_time, $buyer_account, $extends));
            log_message ('heepay_back_success_log', 'order_number:' . $order_number . '  platform_order_number:' . $platform_order_number . 'trading_amount:' . $trading_amount, true);
            echo "success";
        } else {
            log_message ('heepay_back_fail_log', 'error', true);
            echo "fail";
        }
    }

    /**
     * 汇付宝微信支付订单取消
     * @param CancelEventHandler $canceleventhandler
     * @param $order_number 支付内部订单号
     */

    public function cancel(CancelEventHandler $canceleventhandler, $order_number) {
        $trade_no = ''; //汇付宝外部订单传入为“”
        call_user_func_array ($canceleventhandler, array ($order_number, $trade_no));
        log_message ('heepay_cannel_log', 'out_trade_no:' . $order_number, true);
    }

    /**
     * 加载文件信息
     * @param $lib_path
     * @return mixed
     */

    protected function load($lib_path) {
        return include __DIR__.'/'.$lib_path;
    }

    /**
     * 创建sign
     * @param $version
     * @param $agent_id
     * @param $agent_bill_id
     * @param $agent_bill_time
     * @param $pay_type
     * @param $pay_amt
     * @param $notify_url
     * @param $return_url
     * @param $user_ip
     * @param $sign_key
     * @return mixed
     */

    protected function createSign($version, $agent_id, $agent_bill_id, $agent_bill_time,
                                           $pay_type, $pay_amt, $notify_url,$return_url, $user_ip, $sign_key){
        $sign_str  = '';
        $sign_str  = $sign_str . 'version=' . $version;
        $sign_str  = $sign_str . '&agent_id=' . $agent_id;
        $sign_str  = $sign_str . '&agent_bill_id=' . $agent_bill_id;
        $sign_str  = $sign_str . '&agent_bill_time=' . $agent_bill_time;
        $sign_str  = $sign_str . '&pay_type=' . $pay_type;
        $sign_str  = $sign_str . '&pay_amt=' . $pay_amt;
        $sign_str  = $sign_str .  '&notify_url=' . $notify_url;
        $sign_str  = $sign_str .  '&return_url=' . $return_url;
        $sign_str  = $sign_str . '&user_ip=' . $user_ip;
        $sign_str  = $sign_str . '&key=' . $sign_key;
        $sign      = md5($sign_str); //签名值
        return $sign;
    }

    /**
     * 返回异步通知sign
     * @param $config
     * @return string
     */

    protected function returnNotifySign($config){
        $result        = $_GET['result'];  //支付结果：0=正在处理，1=成功，-1=失败
        $pay_message   = $_GET['pay_message'];    //支付结果信息，支付成功时为空
        $agent_id      = $_GET['agent_id'];  //商户编号 如1234567
        $jnet_bill_no  = $_GET['jnet_bill_no'];  //汇付宝交易号(订单号)
        $agent_bill_id = $_GET['agent_bill_id'];    //商户系统内部的定单号
        $pay_type      = $_GET['pay_type'];  //支付类型
        $pay_amt       = $_GET['pay_amt'];    //订单实际支付金额(注意：此金额是用户的实付金额)
        $remark        = $_GET['remark'];  //说明
        $return_sign   = $_GET['sign'];   //MD5签名结果

        $remark        = iconv('gbk','utf-8', urldecode($remark)); //签名验证中的中文采用UTF-8编码;

        //$sign_key      = IS_WAP ? $config['sign_key'] : $config['sign_key_pc'];
        $sign_key      = $config['agent'][$agent_id];

        $sign          = $this->createNotifySign($result, $agent_id, $jnet_bill_no, $agent_bill_id,
            $pay_type, $pay_amt, $remark, $sign_key);

        return $sign;
    }

    /**
     * 创建异步通知sign
     * @param $result
     * @param $agent_id
     * @param $jnet_bill_no
     * @param $agent_bill_id
     * @param $pay_type
     * @param $pay_amt
     * @param $remark
     * @param $sign_key
     * @return string
     */

    protected function createNotifySign($result, $agent_id, $jnet_bill_no, $agent_bill_id,
                                    $pay_type, $pay_amt, $remark, $sign_key){
        $signStr  = '';
        $signStr  = $signStr . 'result=' . $result;
        $signStr  = $signStr . '&agent_id=' . $agent_id;
        $signStr  = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
        $signStr  = $signStr . '&agent_bill_id=' . $agent_bill_id;
        $signStr  = $signStr . '&pay_type=' . $pay_type;

        $signStr  = $signStr . '&pay_amt=' . $pay_amt;
        $signStr  = $signStr .  '&remark=' . $remark;

        $signStr  = $signStr . '&key=' . $sign_key; //商户签名密钥

        $sign     = '';
        file_put_contents('/home/www/www.jiaoyitu.com/application/sign_logs', $signStr  . "\n", FILE_APPEND);
        $sign     = strtolower(md5($signStr));

        return $sign;
    }

    /**
     * 获取IP信息
     * @return string
     */

    protected function getip() {
        if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if(!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = '';
        }
        preg_match("/[\d\.]{7,15}/", $cip, $cips);
        $cip = isset($cips[0]) ? $cips[0] : 'unknown';
        unset($cips);
        return $cip;
    }

}