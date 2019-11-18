<?php
/**
 * 网银支付（目前用的快钱支付:https://www.99bill.com）
 * User: xiongbaoshan
 * Date: 2016/4/15
 * Time: 13:54
 */

namespace Application\Component\Concrete\Payment\Platform\Bankpay;


use Application\Component\Contract\Payment\BackEventHandler;
use Application\Component\Contract\Payment\NotifyEventHandler;

class Payment implements \Application\Component\Contract\Payment\Payment
{
    public function submit($total_fee, $order_number, $order_name, $order_description)
    {
        //人民币网关账号,该账号为11位人民币网关商户编号+01,该参数必填。
        $merchantAcctId = "1002297743301";
        //编码方式,1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
        $inputCharset = "1";
        //接收支付结果的页面地址,该参数一般置为空即可。
        $pageUrl = "";
        //服务器接收支付结果的后台地址,该参数务必填写,不能为空。
        $bgUrl = base_url()."/payment_gateway/notify/bankpay";
        //网关版本,固定值：v2.0,该参数必填。
        $version =  "v2.0";
        //语言种类,1代表中文显示,2代表英文显示。默认为1,该参数必填。
        $language =  "1";
        //签名类型,该值为4,代表PKI加密方式,该参数必填。
        $signType =  "4";
        //支付人姓名,可以为空。
        $payerName= "";
        //支付人联系类型,1 代表电子邮件方式；2 代表手机联系方式。可以为空。
        $payerContactType =  "";
        //支付人联系方式,与payerContactType设置对应,payerContactType为1,则填写邮箱地址；payerContactType为2,则填写手机号码。可以为空。
        $payerContact =  "";
        //商户订单号,以下采用时间来定义订单号,商户可以根据自己订单号的定义规则来定义该值,不能为空。
        $orderId = $order_number;
        //订单金额,金额以“分”为单位,商户测试以1分测试即可,切勿以大金额测试。该参数必填。
        $orderAmount = $total_fee*100;
        //订单提交时间,格式：yyyyMMddHHmmss,如：20071117020101,不能为空。
        $orderTime = date("YmdHis");
        //商品名称,可以为空。
        $productName= $order_name;
        //商品数量,可以为空。
        $productNum = "";
        //商品代码,可以为空。
        $productId = "";
        //商品描述,可以为空。
        $productDesc = $order_description;
        //扩展字段1,商户可以传递自己需要的参数,支付完快钱会原值返回,可以为空。
        $ext1 = "";
        //扩展自段2,商户可以传递自己需要的参数,支付完快钱会原值返回,可以为空。
        $ext2 = "";
        //支付方式,一般为00,代表所有的支付方式。如果是银行直连商户,该值为10,必填。
        $payType = "00";
        //银行代码,如果payType为00,该值可以为空；如果payType为10,该值必须填写,具体请参考银行列表。
        $bankId = "";
        //同一订单禁止重复提交标志,实物购物车填1,虚拟产品用0。1代表只能提交一次,0代表在支付不成功情况下可以再提交。可为空。
        $redoFlag = "";
        //快钱合作伙伴的帐户号,即商户编号,可为空。
        $pid = "";
        // signMsg 签名字符串 不可空,生成加密签名串

        function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){$kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}


        $kq_all_para=kq_ck_null($inputCharset,'inputCharset');
        $kq_all_para.=kq_ck_null($pageUrl,"pageUrl");
        $kq_all_para.=kq_ck_null($bgUrl,'bgUrl');
        $kq_all_para.=kq_ck_null($version,'version');
        $kq_all_para.=kq_ck_null($language,'language');
        $kq_all_para.=kq_ck_null($signType,'signType');
        $kq_all_para.=kq_ck_null($merchantAcctId,'merchantAcctId');
        $kq_all_para.=kq_ck_null($payerName,'payerName');
        $kq_all_para.=kq_ck_null($payerContactType,'payerContactType');
        $kq_all_para.=kq_ck_null($payerContact,'payerContact');
        $kq_all_para.=kq_ck_null($orderId,'orderId');
        $kq_all_para.=kq_ck_null($orderAmount,'orderAmount');
        $kq_all_para.=kq_ck_null($orderTime,'orderTime');
        $kq_all_para.=kq_ck_null($productName,'productName');
        $kq_all_para.=kq_ck_null($productNum,'productNum');
        $kq_all_para.=kq_ck_null($productId,'productId');
        $kq_all_para.=kq_ck_null($productDesc,'productDesc');
        $kq_all_para.=kq_ck_null($ext1,'ext1');
        $kq_all_para.=kq_ck_null($ext2,'ext2');
        $kq_all_para.=kq_ck_null($payType,'payType');
        $kq_all_para.=kq_ck_null($bankId,'bankId');
        $kq_all_para.=kq_ck_null($redoFlag,'redoFlag');
        $kq_all_para.=kq_ck_null($pid,'pid');


        $kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);



        /////////////  RSA 签名计算 ///////// 开始 //
        $fp = fopen(__DIR__."/lib/99bill-rsa.pem", "r");
        $priv_key = fread($fp, 123456);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key);

        // compute signature
        openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

        // free the key from memory
        openssl_free_key($pkeyid);

         $signMsg = base64_encode($signMsg);
        /////////////  RSA 签名计算 ///////// 结束 //
        ?>


        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
        <head>
            <title>正在跳转中。。。</title>
            <meta http-equiv=Content-Type content="text/html;charset=utf-8">
        </head>
        <body>
            <form id="payment_form" name="kqPay" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">
                <input type="hidden" name="inputCharset" value="<?PHP echo $inputCharset; ?>" />
                <input type="hidden" name="pageUrl" value="<?PHP echo $pageUrl; ?>" />
                <input type="hidden" name="bgUrl" value="<?PHP echo $bgUrl; ?>" />
                <input type="hidden" name="version" value="<?PHP echo $version; ?>" />
                <input type="hidden" name="language" value="<?PHP echo $language; ?>" />
                <input type="hidden" name="signType" value="<?PHP echo $signType; ?>" />
                <input type="hidden" name="signMsg" value="<?PHP echo $signMsg; ?>" />
                <input type="hidden" name="merchantAcctId" value="<?PHP echo $merchantAcctId; ?>" />
                <input type="hidden" name="payerName" value="<?PHP echo $payerName; ?>" />
                <input type="hidden" name="payerContactType" value="<?PHP echo $payerContactType; ?>" />
                <input type="hidden" name="payerContact" value="<?PHP echo $payerContact; ?>" />
                <input type="hidden" name="orderId" value="<?PHP echo $orderId; ?>" />
                <input type="hidden" name="orderAmount" value="<?PHP echo $orderAmount; ?>" />
                <input type="hidden" name="orderTime" value="<?PHP echo $orderTime; ?>" />
                <input type="hidden" name="productName" value="<?PHP echo $productName; ?>" />
                <input type="hidden" name="productNum" value="<?PHP echo $productNum; ?>" />
                <input type="hidden" name="productId" value="<?PHP echo $productId; ?>" />
                <input type="hidden" name="productDesc" value="<?PHP echo $productDesc; ?>" />
                <input type="hidden" name="ext1" value="<?PHP echo $ext1; ?>" />
                <input type="hidden" name="ext2" value="<?PHP echo $ext2; ?>" />
                <input type="hidden" name="payType" value="<?PHP echo $payType; ?>" />
                <input type="hidden" name="bankId" value="<?PHP echo $bankId; ?>" />
                <input type="hidden" name="redoFlag" value="<?PHP echo $redoFlag; ?>" />
                <input type="hidden" name="pid" value="<?PHP echo $pid; ?>" />
            </form>
            <script type="text/javascript">
                document.getElementById('payment_form').submit();
            </script>
        </body>
        </html>

        <?php
    }

    public function back(BackEventHandler $back_event_handler,$platform)
    {
        //商户订单号
        $order_number = $_REQUEST['orderId'];

        //交易号
        $platform_order_number = $_REQUEST['dealId'];

        //交易金额
        $trading_amount = $_REQUEST['orderAmount']/100;

        //交易时间
        $trading_time = strtotime($_REQUEST['orderTime']);


        //事件回调
        call_user_func_array($back_event_handler,array($order_number,$platform_order_number,$trading_amount,$trading_time,$platform));
    }

    public function notify(NotifyEventHandler $notify_event_handler)
    {

        function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
        //人民币网关账号,该账号为11位人民币网关商户编号+01,该值与提交时相同。
        $kq_check_all_para=kq_ck_null($_REQUEST['merchantAcctId'],'merchantAcctId');
        //网关版本,固定值：v2.0,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['version'],'version');
        //语言种类,1代表中文显示,2代表英文显示。默认为1,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['language'],'language');
        //签名类型,该值为4,代表PKI加密方式,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['signType'],'signType');
        //支付方式,一般为00,代表所有的支付方式。如果是银行直连商户,该值为10,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['payType'],'payType');
        //银行代码,如果payType为00,该值为空；如果payType为10,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['bankId'],'bankId');
        //商户订单号,,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['orderId'],'orderId');
        //订单提交时间,格式：yyyyMMddHHmmss,如：20071117020101,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['orderTime'],'orderTime');
        //订单金额,金额以“分”为单位,商户测试以1分测试即可,切勿以大金额测试,该值与支付时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['orderAmount'],'orderAmount');
        // 快钱交易号,商户每一笔交易都会在快钱生成一个交易号。
        $kq_check_all_para.=kq_ck_null($_REQUEST['dealId'],'dealId');
        //银行交易号 ,快钱交易在银行支付时对应的交易号,如果不是通过银行卡支付,则为空
        $kq_check_all_para.=kq_ck_null($_REQUEST['bankDealId'],'bankDealId');
        //快钱交易时间,快钱对交易进行处理的时间,格式：yyyyMMddHHmmss,如：20071117020101
        $kq_check_all_para.=kq_ck_null($_REQUEST['dealTime'],'dealTime');
        //商户实际支付金额 以分为单位。比方10元,提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
        $kq_check_all_para.=kq_ck_null($_REQUEST['payAmount'],'payAmount');
        //费用,快钱收取商户的手续费,单位为分。
        $kq_check_all_para.=kq_ck_null($_REQUEST['fee'],'fee');
        //扩展字段1,该值与提交时相同
        $kq_check_all_para.=kq_ck_null($_REQUEST['ext1'],'ext1');
        //扩展字段2,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($_REQUEST['ext2'],'ext2');
        //处理结果, 10支付成功,11 支付失败,00订单申请成功,01 订单申请失败
        $kq_check_all_para.=kq_ck_null($_REQUEST['payResult'],'payResult');
        //错误代码 ,请参照《人民币网关接口文档》最后部分的详细解释。
        $kq_check_all_para.=kq_ck_null($_REQUEST['errCode'],'errCode');

        $trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
        $MAC=base64_decode($_REQUEST['signMsg']);

        $fp = fopen(__DIR__."/lib/99bill.cert.rsa.20340630.cer", "r");
        $cert = fread($fp, 8192);
        fclose($fp);
        $pubkeyid = openssl_get_publickey($cert);
        $ok = openssl_verify($trans_body, $MAC, $pubkeyid);

        $orderId = $_REQUEST['orderId'];
        $dealId = $_REQUEST['dealId'];
        $money = $_REQUEST['orderAmount'] / 100;	//	单位元
        $trading_time=strtotime($_REQUEST['dealTime']);
        $buyer_account = $_REQUEST['bankDealId'];

        if($ok!=1){
            exit('sign verify fail');
        }
        if($_REQUEST['payResult'] != 10){
            exit('pay fail');
        }

        //事件回调
        call_user_func_array($notify_event_handler,array($orderId,$dealId,$money,$trading_time,$buyer_account));
        echo "<result>1</result><redirecturl>".base_url()."/payment_gateway/back/bankpay</redirecturl>";
    }

    public function query($order_number){

    }

}