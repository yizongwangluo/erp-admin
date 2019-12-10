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
        $this->token = $this->get_token(); //获取token

        if(!$this->token || !$this->time_M){
            $this->ajax_return(AJAX_RETURN_FAIL,'未获取到token 或者时间戳');
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
        $url = 'https://open.tongtool.com/open-platform-service/devApp/appToken?accessKey='.$this->APP_Key.'&secretAccessKey='.$this->secret;
        $ret = $this->curl_get_https($url);
        $ret = json_decode($ret,true);
        if($ret['success']){
            return $ret['datas'];
        }
    }

    /**
     * 获取partnerOpenId（merchantId加密后）
     * @return mixed
     */
    public function get_partnerOpenId(){
        $sign = $this->get_sign(['app_token'=>$this->token,'timestamp'=>$this->time_M]);
        $url = 'https://open.tongtool.com/open-platform-service/partnerOpenInfo/getAppBuyerList?app_token='.$this->token.'&timestamp='.$this->time_M.'&sign='.$sign;
        $ret = $this->curl_get_https($url);
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
        //获取merchantId
//        $merchantId_arr = $this->get_partnerOpenId($thi,$arr['timestamp']);

        echo "<br/>";
        print_r($this->merchantId) ;
        exit;
        $ret = $this->json_post($url,$data);

        echo $ret;exit;
    }

    /**
     * 获取订单
     * @param string $accountCode 店铺代码
     * @param string $updatedDateFrom 更新开始时间
     * @param string $updatedDateTo 更新结束时间
     */
    public function get_order($data = []){

        $url = $this->get_link('/openapi/tongtool/ordersQuery');

        $data = ['accountCode'=>'SQ','merchantId'=>$this->merchantId[0]['partnerOpenId'],'storeFlag'=>'0','updatedDateFrom'=>'2019-12-09 00:00:00','updatedDateTo'=>'2019-12-10 00:00:00'];

        $ret = $this->curl_post_https_json($url,$data);

        $ret = json_decode($ret,true);

        //记录日志
        log_message ( 'erp_get_order', json_encode($ret), true );
        //记录日志end

        if($ret['code']!=200){ //请求失败
            $this->ajax_return(AJAX_RETURN_FAIL,$ret['message']);
        }

        $this->ajax_return(AJAX_RETURN_SUCCESS,'ok',['data'=>$ret['datas']]);

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
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }

    /**
     * get请求https链接公共方法(json)
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