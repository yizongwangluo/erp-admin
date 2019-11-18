<?php
/**
 * 支付宝
 * User: xiongbaoshan
 * Date: 2016/4/6
 * Time: 15:51
 */

namespace Application\Component\Concrete\Payment\Platform\Alipay;

use Application\Component\Contract\Payment\BackEventHandler;
use Application\Component\Contract\Payment\CancelEventHandler;
use Application\Component\Contract\Payment\NotifyEventHandler;

class Payment implements \Application\Component\Contract\Payment\Payment
{
    public function __construct()
    {
        //检测是否手机端
        if(!defined('IS_WAP')){
            define('IS_WAP',false);
        }
        $this->load("lib/alipay_core.function.php");
        $this->load("lib/alipay_rsa.function.php");
        $this->config=$this->load("alipay.config.php");

    }

    public function submit($total_fee, $order_number, $order_name, $order_description='')
    {
        $this->load("lib/alipay_submit.class.php");

        /**************************请求参数**************************/

        //商户订单号,商户网站订单系统中唯一订单号,必填
        $out_trade_no = $order_number;
        //订单名称,必填
        $subject = $order_name;
        //付款金额,必填
        $total_fee = $total_fee;
        //商品描述,可空
        $body = $order_description;

        /************************************************************/

        //构造要请求的参数数组,无需改动
        $parameter = array(
            "service"       => $this->config['service'],
            "partner"       => $this->config['partner'],
            "seller_id"  => $this->config['seller_id'],
            "payment_type"	=> $this->config['payment_type'],
            "notify_url"	=> $this->config['notify_url'],
            "return_url"	=> $this->config['return_url'],

            "anti_phishing_key"=>$this->config['anti_phishing_key'],
            "exter_invoke_ip"=>$this->config['exter_invoke_ip'],
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "body"	=> $body,
            "_input_charset"	=> trim(strtolower($this->config['input_charset']))
            //其他业务参数根据在线开发文档,添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
            //如"参数名"=>"参数值"
        );
        //移动端支付
        if(IS_WAP){
            $parameter["show_url"]='';
		    $parameter["app_pay"]="Y";
        }
	    echo "<html><meta name=\"viewport\" content=\"width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no\" />
              <body>请稍等，正在跳转至【支付宝】...</body></html>";
        //建立请求
        $alipaySubmit = new \AlipaySubmit($this->config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo "<html><body>{$html_text}</body></html>";
    }

    public function notify(NotifyEventHandler $notify_event_handler)
    {
        $this->load("lib/alipay_notify.class.php");

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($this->config);
        if(!$alipayNotify->verifyNotify()) {
            //验证失败
	        \log_message ('alipay_notify','fail',\true);
            echo "fail";exit();
        }

        //只处理支付成功的通知
        if (!$_POST['trade_status'] == 'TRADE_SUCCESS') {
            exit();
        }

        //商户订单号
        $order_number = $_POST['out_trade_no'];

        //支付宝交易号
        $platform_order_number = $_POST['trade_no'];

        //交易金额
        $trading_amount = $_POST['total_fee'];

        //交易时间
        $trading_time = strtotime($_POST['gmt_payment']);

        //买家支付账号
        $buyer_account = $_POST['buyer_email'];


        //事件回调
        call_user_func_array($notify_event_handler,array($order_number,$platform_order_number,$trading_amount,$trading_time,$buyer_account));

        echo "success";
    }


    public function back(BackEventHandler $back_event_handler)
    {
        $this->load("lib/alipay_notify.class.php");

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($this->config);
        $verify_result = $alipayNotify->verifyReturn();
        if(!$verify_result) {
            //验证失败
            echo "sign check fail";exit();
        }

        //商户订单号
        $order_number = $_GET['out_trade_no'];

        //支付宝交易号
        $platform_order_number = $_GET['trade_no'];

        //交易金额
        $trading_amount = $_GET['total_fee'];

        //交易时间
        $trading_time = strtotime($_GET['gmt_payment']);

        //买家账号
	    $buyer_email = $_GET['buyer_email'];

        //只处理支付成功的通知
        if (!$_GET['trade_status'] == 'TRADE_SUCCESS') {
            exit();
        }

        //事件回调
        call_user_func_array($back_event_handler,array($order_number,$platform_order_number,$trading_amount,$trading_time,$buyer_email));
    }

	//支付宝内部单号
    public function query($order_number)
    {
	    $this->load("lib/alipay_query.class.php");
	    $parameter = array(
		    'out_trade_no'=>$order_number
	    );
	    $aliCancel =  new \AliQuery($this->config);
	    $trade_no = $aliCancel->buildRequestUrl ($parameter);
	    return $trade_no;
    }

	//支付宝内部单号
    public function cancel(CancelEventHandler $canceleventhandler,$order_number){
	    $this->load("lib/alipay_cancel.class.php");
	    $parameter = array(
	    	'out_trade_no'=>$order_number
	    );
	    $aliCancel =  new \AliCancel($this->config);
	    $trade_no = $aliCancel->buildRequestUrl ($parameter);
		call_user_func_array ($canceleventhandler,array ($order_number,$trade_no));
    }

    protected function load($lib_path){
        return include_once __DIR__.'/'.$lib_path;
    }

}