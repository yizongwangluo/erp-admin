<?php

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';
require_once 'log.php';

class WxPayNotifyCallBack extends WxPayNotify {

    private $logHandler = null;

    private $notifyEventHandler = null;

    public function __construct ( \Application\Component\Contract\Payment\NotifyEventHandler $notify_event_handler ) {
        //初始化日志
        $this->logHandler = new \CLogFileHandler ( APPPATH . 'logs/' . date('Y-m-d') . '.log' );
        \Log::Init($this->logHandler, 15);
        $this->notifyEventHandler = $notify_event_handler;
    }

    //查询订单
    public function Queryorder ($transaction_id) {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        \Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess ($data, &$msg) {
        \Log::DEBUG("call back:" . json_encode($data));

        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }

        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }

        //商户订单号
        $order_number          = $data['out_trade_no'];
        //微信支付订单号
        $platform_order_number = $data['transaction_id'];
        //交易金额
        $trading_amount        = bcdiv($data['total_fee'], 100, 2);
        //交易时间
        $trading_time          = $data['time_end'];
        //买家支付账号
        $buyer_account         = '';

        //事件回调
        call_user_func_array($this->notifyEventHandler, array($order_number, $platform_order_number, $trading_amount, $trading_time, $buyer_account));

        return true;
    }

    //手动通知处理
    public function ManualNotifyProcess () {

        $data = ['out_trade_no' => 'TRADE20180525141946940352542232', 'transaction_id' => '4200000113201805255919785615', 'total_fee' => '11', 'time_end' => '20180525142009'];

        //商户订单号
        $order_number          = $data['out_trade_no'];
        //微信支付订单号
        $platform_order_number = $data['transaction_id'];
        //交易金额
        $trading_amount        = bcdiv($data['total_fee'], 100, 2);
        //交易时间
        $trading_time          = $data['time_end'];
        //买家支付账号
        $buyer_account         = '';

        //事件回调
        call_user_func_array($this->notifyEventHandler, array($order_number, $platform_order_number, $trading_amount, $trading_time, $buyer_account));

        return true;
    }

}