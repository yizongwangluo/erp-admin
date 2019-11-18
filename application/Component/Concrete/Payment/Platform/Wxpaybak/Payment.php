<?php
/**
 * 微信支付（使用微付通平台）
 * User: xiongbaoshan
 * Date: 2016/4/15
 * Time: 10:37
 */

namespace Application\Component\Concrete\Payment\Platform\Wxpay;


use Application\Component\Contract\Payment\BackEventHandler;
use Application\Component\Contract\Payment\NotifyEventHandler;

class Payment extends \MY_Model implements \Application\Component\Contract\Payment\Payment
{
    private $resHandler = null;
    private $reqHandler = null;
    private $pay = null;
    private $cfg = null;

    public function __construct()
    {
        $this->load('lib/Config.php');
        $this->load('lib/Utils.class.php');
        $this->load('lib/RequestHandler.class.php');
        $this->load('lib/ClientResponseHandler.class.php');
        $this->load('lib/PayHttpClient.class.php');

        $this->resHandler = new \ClientResponseHandler();
        $this->reqHandler = new \RequestHandler();
        $this->pay = new \PayHttpClient();
        $this->cfg = new \Config();

        $this->reqHandler->setGateUrl($this->cfg->C('url'));
        $this->reqHandler->setKey($this->cfg->C('key'));

    }

    public function submit($total_fee, $order_number, $order_name, $order_description)
    {
        $request_params=array(
            'out_trade_no'=>$order_number,
            'body'=>$order_name,
            'attach'=>'',
            'total_fee'=>$total_fee*100,
            'mch_create_ip'=>$_SERVER['REMOTE_ADDR'],
            'time_start'=>date('YmdHis'),
            'time_expire'=>date('YmdHis',time()+300),
        );
        $this->reqHandler->setReqParams($request_params,array('method'));
        $this->reqHandler->setParameter('service','pay.weixin.scancode');//接口类型：pay.weixin.scancode
        $this->reqHandler->setParameter('mch_id',$this->cfg->C('mchId'));//必填项,商户号,由威富通分配
        $this->reqHandler->setParameter('notify_url',$this->cfg->C('notify_url'));
        $this->reqHandler->setParameter('version',$this->cfg->C('version'));
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串,必填项,不长于 32 位
        $this->reqHandler->createSign();//创建签名

        $data = \Utils::toXml($this->reqHandler->getAllParameters());
        //var_dump($data);

        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回支付二维码,其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){

                    //二维码信息
                    $qrcode_info=array(
                        'out_trade_no'=>$order_number,
                        'total_fee'=>$total_fee,
                        'order_name'=>$order_name,
                        'img_url'=>$this->resHandler->getParameter('code_img_url'),
                    );
                    include(__DIR__.'/qrcode_display.php');
                    exit;
                }else{
                    echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg')));
                    exit();
                }
            }
            echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message')));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }

    }

    public function back(BackEventHandler $back_event_handler,$platform)
    {
        $order_number=$_GET['out_trade_no'];

        //事件回调
        call_user_func_array($back_event_handler,array($order_number,'','','','',$platform));
    }

    public function notify(NotifyEventHandler $notify_event_handler)
    {
        $xml = file_get_contents('php://input');
        $this->resHandler->setContent($xml);
        $this->resHandler->setKey($this->cfg->C('key'));
        if($this->resHandler->isTenpaySign()){
            if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){

                //事件回调
                call_user_func_array($notify_event_handler,array(
                    $this->resHandler->getParameter('out_trade_no'),
                    $this->resHandler->getParameter('transaction_id'),
                    $this->resHandler->getParameter('total_fee')/100,
                    strtotime($this->resHandler->getParameter('time_end')),
                    $this->resHandler->getParameter('openid')
                ));
                echo 'success';
                exit();
            }else{
                echo 'failure';
                exit();
            }
        }else{
            echo 'failure';
        }
    }


    protected function load($lib_path){
        return include_once __DIR__.'/'.$lib_path;
    }

}