<?php
set_time_limit ( 0 );
class Shopify_orders extends \Application\Component\Common\IFacade
{
    public function __construct ()
    {

        parent::__construct ();
        $this->load->model ( 'data/order_synchro_data' );
        $this->load->model ( 'data/order_synchro_log_data' );
        $this->load->model ( 'data/order_data' );
        $this->load->model ( 'data/order_goods_data' );
        $this->load->model ( 'data/shop_data' );
    }


    public function index(){

        $shop_list = $this->shop_data->lists(['status'=>1]);//状态为开启的店铺

        //没有店铺时 跳出程序
        if(empty($shop_list)){
            return false;
        }

        $time = date('Y-m-d',strtotime("-1 day"));
        $min_time = $time.'T00:00:00';
        $mix_time = $time.'T23:59:59';

        foreach($shop_list as $k=>$value){

            foreach($value as $i=>$item){ //去除文字空格
                $value[$i] = trim($item);
            }

            if($value['shop_api_key'] && $value['shop_api_pwd'] && $value['backstage']){
                $url = 'https://'.$value['shop_api_key'].':'.$value['shop_api_pwd'].'@'.$value['backstage'].'api/2020-01/orders.json?order=updated_at&updated_at_min='.$min_time.'&updated_at_max='.$mix_time.'&limit=250';
//                $url = 'http://www.erp.com/ceshi.json';
                $status = 1;
                $this->get_order_page( $status,$value,$url,$time,$min_time,$mix_time);
            }
        }
    }

    /**
     * 同步shopify订单
     * @return bool
     */
    public function sync_order(){

        $shop_list = $this->shop_data->lists(['status'=>1]);//状态为开启的店铺

        //没有店铺时 跳出程序
        if(empty($shop_list)){
            return false;
        }

        $time = date('Y-m-d',time());
//        $min_time = date('Y-m-d\TH:i:s', strtotime("-30 minute"))."-08:00";
//        $mix_time = date('Y-m-d\TH:i:s', time())."-08:00";
        $min_time = date('Y-m-d\TH:i:s', strtotime("-510 minute"));
        $mix_time = date('Y-m-d\TH:i:s', strtotime("-480 minute"));

        foreach($shop_list as $k=>$value){

            foreach($value as $i=>$item){ //去除文字空格
                $value[$i] = trim($item);
            }

            if($value['shop_api_key'] && $value['shop_api_pwd'] && $value['backstage']){
                $url = 'https://'.$value['shop_api_key'].':'.$value['shop_api_pwd'].'@'.$value['backstage'].'api/2020-01/orders.json?status=any&order=updated_at&updated_at_min='.$min_time.'&updated_at_max='.$mix_time.'&limit=250';
//                $url = 'http://www.erp.com/ceshi.json';
                $status = 1;
                $this->get_order_page( $status,$value,$url,$time,$min_time,$mix_time);
            }
        }
    }
    /**
     * 同步shopify订单
     * @return bool
     */
    public function sync_order_date($min_time,$mix_time){

        $shop_list = $this->shop_data->lists(['status'=>1]);//状态为开启的店铺

        //没有店铺时 跳出程序
        if(empty($shop_list)){
            return false;
        }

        $time = date('Y-m-d',time());
        $min_time = date('Y-m-d\TH:i:s', (strtotime($min_time)-30600));
        $mix_time = date('Y-m-d\TH:i:s', (strtotime($mix_time)-480*60));

        foreach($shop_list as $k=>$value){

            foreach($value as $i=>$item){ //去除文字空格
                $value[$i] = trim($item);
            }

            if($value['shop_api_key'] && $value['shop_api_pwd'] && $value['backstage']){
                $url = 'https://'.$value['shop_api_key'].':'.$value['shop_api_pwd'].'@'.$value['backstage'].'api/2020-01/orders.json?status=any&order=updated_at&updated_at_min='.$min_time.'&updated_at_max='.$mix_time.'&limit=250';
//                $url = 'http://www.erp.com/ceshi.json';
                $status = 1;
                $this->get_order_page( $status,$value,$url,$time,$min_time,$mix_time);
            }
        }
    }

    /**
     * 请求地址，获取订单并保存
     * @param array $arr
     * @param string $url
     * @param string $time
     * @param string $min_time
     * @param string $mix_time
     * @param int $page
     * @return bool
     */
    public function get_order_page($status = '',$arr = [],$url = '',$time = '',$min_time = '',$mix_time = '',$page = 1){

        //添加同步日志
        $log_id = $this->order_synchro_log_data->add_log($arr['id'],$url,$min_time,$mix_time,$page);
        $order_json = curl_get_https($url);

        //log_message('get_order_page',date('Y-m-d H:i:s').'||||'.$order_json,true);

        $order_list = json_decode($order_json,true);

        if(isset($order_list['errors'])){ //接口报错
            $this->order_synchro_log_data->edit_log($log_id,2,0,0,$order_list['errors']);
            return false;
        }

        $order_cout = count($order_list['orders']);
        $count = 0;
        if($order_list){
            if($order_cout>0){ //有订单时
                //同步订单到本地
                $count = $this->add_order($arr,$order_list['orders'],$status);
            }

            //修改订单同步状态
            $this->order_synchro_log_data->edit_log($log_id,1,$order_cout,$count);

            $next_link = $this->get_header($url,$arr['shop_api_key'],$arr['shop_api_pwd']); //下页链接

            if($next_link){
                $page++;
                $this->get_order_page($status,$arr,$next_link,$time,$min_time,$mix_time,$page);
            }
        }
    }


    /**
     * 保存订单
     * @param array $shopinfo 店铺信息
     * @param array $orderlist 订单列表
     * @param $status
     * @return int
     */
    public function add_order($shopinfo = [],$orderlist = [],$status){
        $count = 0;
        $time = date('Y-m-d H:i:s');
        foreach($orderlist as $v){
            $order_info = [];
            $order_info['shopify_o_id'] = $v['id'];
            $order_info['shop_id'] = $shopinfo['id'];
            $order_info['user_id'] = $shopinfo['user_id'];
            $order_info['total_price_usd'] = $v['total_price_usd'];
            $order_info['created_at'] = $v['created_at'];
            $order_info['updated_at'] = $v['updated_at'];
            $order_info['processed_at'] = $v['processed_at'];
            $order_info['total_weight'] = $v['total_weight'];
            $order_info['financial_status'] = $v['financial_status'];
            $order_info['gateway'] = $v['gateway'];
            $order_info['addtime'] = $time; //新增时间
            $order_info['tracking_number'] = $v['fulfillments'][0]['tracking_number'];
            $order_info['datetime'] = ConversionTime($v['processed_at']);
            $order_id = $this->order_data->save($order_info);

            if($order_id){
                foreach($v['line_items'] as $item){
                    $order_goods_info = [];
                    $order_goods_info['o_id'] = $order_id;
                    $order_goods_info['product_id'] = $item['product_id'];
                    $order_goods_info['sku_id'] = $item['sku'];
                    $order_goods_info['shopify_o_id'] = $v['id'];
                    $order_goods_info['quantity'] = $item['quantity'];
                    $order_goods_info['shop_id'] = $shopinfo['id'];
                    $order_goods_info['user_id'] = $shopinfo['user_id'];
                    $order_goods_info['datetime'] = $order_info['datetime'];
                    $order_goods_info['shopify_goods_id'] = $item['id'];
                    $order_goods_info['addtime'] = $time;

                    $this->order_goods_data->store($order_goods_info,true);
                }
                $count++;
            }
        }
        return $count;
    }

    /**
     * get请求https链接公共方法
     * @param $url
     * @return mixed
     */
    function curl_get_shopify_https($url){
        //	return $url;
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        // 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
        curl_setopt($curl, CURLOPT_HEADER, true);
        $tmpInfo = curl_exec($curl);     //返回api的json对象

        // 获得响应结果里的：头大小
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        // 根据头大小去获取头信息内容
        $header = substr($tmpInfo, 0, $headerSize);
        //关闭URL请求
        curl_close($curl);

        return $tmpInfo;    //返回json对象
    }

    /**
     * 解析响应头
     * @param string $url
     * @param string $key
     * @param string $password
     * @return bool|mixed|string
     */
    public function get_header($url = '',$key = '',$password = ''){

        $headArr=get_headers($url);

        foreach ($headArr as $v) {
            if(strpos($v,'Link')!==false){
                $link = explode(',',$v);
                foreach($link as $item){
                    if(strpos($item,'next')!==false){
                        $a = ['Link:','<https://','>',';',' ','rel="next"'];
                        $url = str_replace($a,'',$item);
                        if($url){
                            $url = 'https://'.$key.':'.$password.'@'.$url;
                            return $url;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * 获取交易号
     */
    public function get_transaction(){

    }


}