<?php
set_time_limit ( 0 );
use  Application\Component\Concrete\TongTuApi\ErpApiFactory;

class Tongtu_orders extends \Application\Component\Common\IFacade
{
    static protected $rateList = null;
    static protected $shopList = null;
    static protected $limit_times = 5; //请求次数限制
    static protected $limit = 100; //每页条数限制
    public function __construct()
    {
        parent::__construct();
        $this->load->model ( 'data/order_synchro_log_data' );
        $this->load->model ( 'data/shop_data' );
        $this->load->model ( 'data/admin_exchange_rate_data' );
        $this->load->model ( 'data/order_data' );
        $this->load->model ( 'data/order_goods_data' );
    }

    /**
     * 获取汇率
     * @param string $code
     * @return int
     */
    public function get_rate($code = ''){

        if($code=='USD'){
            return 1;
        }

        if(!self::$rateList){
            self::$rateList = $this->admin_exchange_rate_data->get_field_by_where(['currency_code','rate'],[],true);
            self::$rateList = array_column(self::$rateList,'rate','currency_code');
        }
        return self::$rateList[$code] ? self::$rateList[$code]: 1;
    }

    /**
     * 获取店铺列表
     * @param string $code
     * @return string
     */
    public function get_shop($code = ''){
        if(!self::$shopList){
            self::$shopList = $this->shop_data->get_field_by_where(['id','code','user_id'],['code <>'=>''],true);
            self::$shopList = array_column(self::$shopList,null,'code');
        }
        return self::$shopList[$code] ? self::$shopList[$code]: '';
    }

    /**
     * 同步订单
     * @return bool
     */
    public function get_order(){

        $this->erpApi = new ErpApiFactory();

//        $yesterday_time = strtotime("-1 day");
        $time = time();
        $data = [];
//        $data['updatedDateFrom'] = date('Y-m-d H:i:s',$yesterday_time-(5*60));
//        $data['updatedDateTo'] = date('Y-m-d H:i:s',$yesterday_time);
        $data['updatedDateFrom'] = date('Y-m-d H:i:s',$time-(35*60));
        $data['updatedDateTo'] = date('Y-m-d H:i:s',$time-(30*60));
        $data['pageNo'] = 1;

        for($data['pageNo'];$data['pageNo']<=self::$limit_times;$data['pageNo']++){ //通途接口，短时间内最多请求5次

            $log_id = $this->order_synchro_log_data->add_log(0,'tongtu',$data['updatedDateFrom'],$data['updatedDateTo'],$data['pageNo']);

            $ret = $this->erpApi->get_order($data);

            //列表获取失败
            if($ret['code']!=200){
                $this->order_synchro_log_data->edit_log($log_id,2,0,0,$ret['message']);
                break;
            }else{

                $order_cout = count($ret['datas']['array']);

                $count = $this->add_order($ret['datas']['array']);

                //修改订单同步状态
                $this->order_synchro_log_data->edit_log($log_id,1,$order_cout,$count);

                if($order_cout<self::$limit){ break; } //未满指定条时，跳出循环
            }
        }

        return true;
    }

    /**
     * 同步指定条件的订单
     * @return bool
     */
    public function get_order_in_shop(){

        $Time = date('i'); //获取分

        if($Time%5){

            $json = file_get_contents(FCPATH.'duilie.json');

            $json = json_decode($json,true);

            $arr = $json['data'];

            if(count($arr)<1){ return true; }

            $this->erpApi = new ErpApiFactory();

            $data = [];
            $data['updatedDateFrom'] = $arr[0]['start_time'];
            $data['updatedDateTo'] = $arr[0]['end_time'];
            $data['code'] = $arr[0]['code'];
            $data['pageNo'] = $arr[0]['pageNo'];

            for($page = 0;$page<self::$limit_times;$page++){ //通途接口，短时间内最多请求5次

                log_message('get_order_in_shop',json_encode($data),true);

                $ret = $this->erpApi->get_order($data);

                //列表获取失败
                if($ret['code']!=200){

                    log_message('get_order_in_shop','error----'.json_encode($data),true);

                    break;
                }else{
                    $data['pageNo']++;

                    $order_cout = count($ret['datas']['array']);

                    $count = $this->add_order($ret['datas']['array']);

                    if($data['pageNo']%self::$limit_times == 0){ //保存记录
                        $arr[0]['pageNo'] = $data['pageNo']+1;
                        $json['data'] = $arr;
                        file_put_contents(FCPATH.'duilie.json',json_encode($json));
                    }

                    if($order_cout<self::$limit){ //未满指定条时，跳出循环
                        //同步完成 删除该队列
                        unset($arr[0]);
                        $arr = array_splice($arr,0,count($arr),true);
                        $json['data'] = $arr;
                        file_put_contents(FCPATH.'duilie.json',json_encode($json));
                        break;
                    }
                }
            }
        }
        return true;
    }


    /**
     * 保存订单
     * @param array $orderlist
     * @return int
     */
    public function add_order($orderlist = []){
        $count = 0;
        $time = date('Y-m-d H:i:s');
        foreach($orderlist as $v){
            $order_info = [];

            $a = explode('-',$v['orderIdCode']);
            $shopinfo = $this->get_shop($a[0]);
            $rate = $this->get_rate($v['taxCurrency']);

            $order_info['shopify_o_id'] = $v['orderIdCode'];
            $order_info['shop_id'] = isset($shopinfo['id'])?$shopinfo['id']:0;
            $order_info['user_id'] = isset($shopinfo['user_id'])?$shopinfo['user_id']:0;

            $order_info['rate'] = $rate;
            $order_info['price_currency'] = $v['taxCurrency'];
            $order_info['total_price'] = $v['orderAmount'];
            $order_info['total_price_usd'] = bcdiv($v['orderAmount'],$rate,2);

            $order_info['created_at'] = $v['saleTime'];
            $order_info['updated_at'] = $v['saleTime'];
            $order_info['processed_at'] = $v['saleTime'];
            $order_info['total_weight'] = array_sum(array_column($v['goodsInfo']['tongToolGoodsInfoList'],'goodsWeight'));
            $order_info['financial_status'] = 'paid';
            $order_info['orderStatus'] = $v['orderStatus'];
            $order_info['platformCode'] = $v['platformCode'];
            $order_info['gateway'] = $v['gateway'];
            $order_info['addtime'] = $time; //新增时间
            $order_info['tracking_number'] = $v['packageInfoList'][0]['trackingNumber']; //运单号
            $order_info['tracking_type'] = $order_info['tracking_number']?1:0; //运单号
            $order_info['datetime'] = $v['saleTime'];
            $order_id = $this->order_data->save($order_info);

            if($order_id){

                foreach($v['goodsInfo']['platformGoodsInfoList'] as $k=>$item){
                    $order_goods_info = [];
                    $order_goods_info['o_id'] = $order_id;
                    $order_goods_info['product_id'] = $v['goodsInfo']['platformGoodsInfoList'][$k]['webstoreSku'];
                    $order_goods_info['sku_id'] = $item['goodsSku'];
                    $order_goods_info['shopify_o_id'] = $v['orderIdCode'];
                    $order_goods_info['quantity'] = $item['quantity'];
                    $order_goods_info['shop_id'] = $order_info['shop_id'];
                    $order_goods_info['user_id'] = $order_info['user_id'];
                    $order_goods_info['datetime'] = $order_info['datetime'];
                    $order_goods_info['shopify_goods_id'] = $item['webTransactionId'];
                    $order_goods_info['webTransactionId'] = $item['webTransactionId'];
                    $order_goods_info['addtime'] = $time;

                    $this->order_goods_data->store($order_goods_info,true);

                }
                $count++;
            }
        }
        return $count;
    }


    /**
     * 获取物流单号
     * @return bool
     */
    public function get_trackingNumber(){

        $this->erpApi = new ErpApiFactory();

        $data = [];
        $data['pageNo'] = 1;

        //获取需要更新物流单号的的订单ID
        $orderListArr = $this->order_data->get_tracking_list(self::$limit_times*self::$limit);

        $cishu =ceil(count($orderListArr)/self::$limit);//获取循环次数

        for($data['pageNo'];$data['pageNo']<=$cishu;$data['pageNo']++){ //通途接口，短时间内最多请求5次

            $orderArr = array_slice($orderListArr,($data['pageNo']-1)*self::$limit,self::$limit);
            $orderIdArr = array_column($orderArr,'shopify_o_id');
            $idArr = array_column($orderArr,'id');

            $ret = $this->erpApi->get_trackingNumberQuery($orderIdArr);

            log_message('get_trackingNumber',date('Y-m-d H:i:s').'-----'.json_encode($orderIdArr).'-----'.json_encode($ret).'------',true);

            //列表获取失败
            if($ret['code']!=200){
                break;
            }else{

                //查询次数批量加一
                $this->order_data->tracking_number_req_add_one($idArr);

                $arr = [];
                foreach($ret['datas']['array'] as $v){
                    if($v['trackingNumber']!=null){
                        $arr[] = ['shopify_o_id'=>$v['orderId'],'tracking_number'=>$v['trackingNumber'],'tracking_type'=>1];
                    }
                }

                $this->order_data->edit_tracking_number($arr); //修改物流编号
            }
        }

        return true;

    }


}