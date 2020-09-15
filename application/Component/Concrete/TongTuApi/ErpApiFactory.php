<?php
/**
 * 通途对接api抽象类
 * User: liuxiaojie
 * Date: 2019/12/06
 * Time: 09:24
 */

namespace Application\Component\Concrete\TongTuApi;

class ErpApiFactory
{
    private $APP_Key;
    private $secret;
    private $token;
    private $merchantId;
    private $time_M;

    public function __construct ()
    {
        $this->APP_Key = 'a573447c6bce46ebb698515c043ca689';
        $this->secret = '18498c1c39db43daba8e69cc6caa54b92d7aebe7fbe9419ea70419e709663fd6';
        $this->time_M = $this->getMilliseconds();
        if(!$this->time_M){
            $this->ajax_return(AJAX_RETURN_FAIL,'未获取到时间戳');
        }

        $this->token = $this->get_token(); //获取token
        if(!$this->token ){
            $this->ajax_return(AJAX_RETURN_FAIL,'未获取到token ');
        }
        $this->merchantId = $this->get_partnerOpenId(); //获取partnerOpenId（merchantId加密后）

        if(!$this->merchantId){
            $this->ajax_return(AJAX_RETURN_FAIL,'未获取到merchantId ，请联系技术人员排查');
        }
    }

    /**
     * 获得毫秒
     * @return float
     */
    function getMilliseconds(){
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 获取token
     * @return mixed
     */
    public function get_token(){
        /*$url = 'https://open.tongtool.com/open-platform-service/devApp/appToken?accessKey='.$this->APP_Key.'&secretAccessKey='.$this->secret;
        $ret = $this->curl_get_https($url);
        $ret = json_decode($ret,true);
        if($ret['success']){
            return $ret['datas'];
        }*/

        //原本是上面的动态获取的方法，但是通途方说，该token只要在不修改应用信息的情况下，都是永久的
        return 'c59f638ab42a341e18b6c724ab5511df';
    }

    /**
     * 获取partnerOpenId（merchantId加密后）
     * @return mixed
     */
    public function get_partnerOpenId(){
        /*$sign = $this->get_sign(['app_token'=>$this->token,'timestamp'=>$this->time_M]);
        $url = 'https://open.tongtool.com/open-platform-service/partnerOpenInfo/getAppBuyerList?app_token='.$this->token.'&timestamp='.$this->time_M.'&sign='.$sign;
        $ret = $this->curl_get_https($url);*/

        //数据写死是跟token一样的原因
        $ret = '{"success":true,"code":0,"message":"成功","datas":[{"tokenId":"1684c3d41aac40bbaa650beccd5ecfb8","devId":"044500","devAppId":"321339775871090688","accessKey":"a573447c6bce46ebb698515c043ca689","appToken":"c59f638ab42a341e18b6c724ab5511df","appTokenExpireDate":253402214400000,"partnerOpenId":"dfeafa44535ecc1fcbfc7b6032c91f1b","partnerName":"深圳大龙猫网络科技有限公司","userOpenId":"7d6e19b6cf803c13500ef75863f9b044","userName":"刘伟","buyDate":1570589302000,"price":0.0,"createdDate":1570589302000,"createdBy":"201908230006007813","updatedDate":1570589302000,"updatedBy":"201908230006007813"}],"others":null}';
        $ret = json_decode($ret,true);
        if($ret['success']){
            return $ret['datas'];
        }
    }

    /**
     * 同步spu(添加商品)
     * @param array $data
     * @return bool
     */
    public function add_goods($data = []){

        $url = $this->get_link('/openapi/tongtool/createProduct');
        $data['merchantId'] = $this->merchantId[0]['partnerOpenId'];

        $ret = $this->curl_post_https_json($url,$data);
        $ret = json_decode($ret,true);

        $a = ['code'=>true];
        if($ret['code']!=200){
            $a['code'] =false;
            $a['msg']=$ret['message']?$ret['message']:'商品同步到通途失败，请手动同步！';
        }
        return $a;
    }

    /**
     * 获取订单
     * @param array $data
     * @return mixed
     */
    public function get_order($data = []){

        $url = $this->get_link('/openapi/tongtool/ordersQuery');

        //storeFlag 0”查询活跃表，”1”为查询1年表，”2”为查询归档表，默认为”0”

        $data = ['storeFlag'=>0,'merchantId'=>$this->merchantId[0]['partnerOpenId'],'pageNo'=>$data['pageNo'],
//                'orderId'=>'LMb001j-200913161522583'];
                'payDateFrom'=>$data['updatedDateFrom'],'payDateTo'=>$data['updatedDateTo']];
//                'updatedDateFrom'=>$data['updatedDateFrom'],'updatedDateTo'=>$data['updatedDateTo']];

        if(isset($data['code'])){
            $data['accountCode'] = $data['code'];
        }

        $ret = $this->curl_post_https_json($url,$data);
//        $ret = file_get_contents('http://www.erp.com/ceshi.json');

        //记录日志
        if(isset($data['code'])){
            log_message ( 'erp_get_order_in_shop', json_encode($data).'------'.$ret, true );
        }else{
            log_message ( 'erp_get_order', json_encode($data).'------'.$ret, true );
        }
        //记录日志end

        $ret = json_decode($ret,true);

        return $ret;

    }


    /**
     * 获取shopify订单
     * @param string $payDateFrom
     * @param string $payDateTo
     * @param int $pageNo
     * @return mixed
     */
    public function  get_shopify_order($payDateFrom = '',$payDateTo = '',$pageNo = 1){

        $url = $this->get_link('/openapi/tongtool/shopifyOrderQuery');

        $data = ['pageNo'=>$pageNo,
                'merchantId'=>$this->merchantId[0]['partnerOpenId'],
                'payDateFrom'=>$payDateFrom,
                'payDateTo'=>$payDateTo];

        $ret = $this->curl_post_https_json($url,$data);

        $ret = json_decode($ret,true);

        return $ret;

    }

    /**
     * 根据订单ID查询物流单号
     * @param array $orderIds
     * @param int $pageNo
     * @return mixed
     */
    public function get_trackingNumberQuery($orderIds = array(),$pageNo = 1){

        $url = $this->get_link('/openapi/tongtool/trackingNumberQuery');

        $data = ['merchantId'=>$this->merchantId[0]['partnerOpenId'],'orderIds'=>$orderIds,'pageNo'=>$pageNo];

        $ret = $this->curl_post_https_json($url,$data);

//        $ret = file_get_contents('http://erp.vasilijh.com/ceshi.json');

        $ret = json_decode($ret,true);

        return $ret;
    }

    /**
     * 获取完整链接
     * @param string $url
     * @return string
     */
    public function get_link($url =''){

        $arr['app_token'] = $this->token;//当前时间戳
        $arr['timestamp'] = $this->time_M;//当前时间戳
        $sign = $this->get_sign($arr);

        $url = 'https://open.tongtool.com/api-service'.$url.'?'.http_build_query($arr).'&sign='.$sign;

        return $url;
    }

    /**
     * 生成签名
     * @param array $data
     * @return string
     */
    public function get_sign($data = []){

        ksort($data); //排序
        $str = '';
        foreach($data as $k=>$v){
            $str.=$k.$v;
        }
        $sign = md5($str.$this->secret);

        return $sign;
    }

    /**
     * get请求https链接公共方法
     * @param $url
     * @return mixed
     */
    function curl_get_https($url){

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }

    /**
     * post请求https链接公共方法(json)
     * @param $url
     * @return mixed
     */
    function curl_post_https_json($url, $data = NULL)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if(!$data){
            return 'data is null';
        }
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($data),
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'api_version:3.0'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            return $errorno;
        }
        curl_close($curl);
        return $res;

    }

    /**
     * ajax返回数据
     * @param int $status
     * @param string $msg
     * @param array $data
     */
    public function ajax_return ( $status = 0, $msg = '', $data = array () )
    {
        if ( $status ) destroy_verify_code ();
        header ( 'content-type:text/json;charset=utf-8' );
        echo json_encode ( array (
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ) );
        exit( 0 );
    }
}