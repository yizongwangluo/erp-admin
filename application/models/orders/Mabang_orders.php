<?php
set_time_limit ( 0 );
use  Application\Component\Concrete\MaBangApi\ErpApiFactory;

class Mabang_orders extends \Application\Component\Common\IFacade
{
    static protected $rateList = null;
    static protected $shopList = null;
//    static protected $limit_times = 5; //请求次数限制
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
        if($code=='RMB'){
            $code = 'CNY';
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
            self::$shopList = $this->shop_data->get_field_by_where(['id','user_id'],[],true);
            self::$shopList = array_column(self::$shopList,null,'id');
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
        $time = strtotime(date('Y-m-d H:i').':00');
        $data = [];
        $data['updateTimeStart'] = date('Y-m-d H:i:s',$time-(5*60*60+5*60)); //获取五小时前的数据
        $data['updateTimeEnd'] = date('Y-m-d H:i:s',$time-(5*60*60));
        $data['page'] = 1;

        for($data['page'];$data['page']>0;$data['page']++){ //通途接口，短时间内最多请求5次

            $log_id = $this->order_synchro_log_data->add_log(0,'mabang',$data['updateTimeStart'],$data['updateTimeEnd'],$data['page']);

            $ret = $this->erpApi->get_lists($data);

            //列表获取失败
            if($ret['code']!=000){
                $this->order_synchro_log_data->edit_log($log_id,2,0,0,$ret['message']);
                break;
            }else{

                $order_cout = count($ret['data']);

                $count = $this->add_order($ret['data']);

                //修改订单同步状态
                $this->order_synchro_log_data->edit_log($log_id,1,$order_cout,$count);

                if($order_cout<self::$limit){ break; } //未满指定条时，跳出循环
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

            $shopinfo = $this->get_shop($v['shopName']);
            $rate = $this->get_rate($v['currencyId']);

            $o_id = 'mb-'.$v['platformOrderId'];

            $order_info['shopify_o_id'] = $o_id;
            $order_info['shop_id'] = isset($shopinfo['id'])?$shopinfo['id']:0;
            $order_info['user_id'] = isset($shopinfo['user_id'])?$shopinfo['user_id']:0;

            $order_info['rate'] = $rate;
            $order_info['price_currency'] = $v['currencyId'];
            $order_info['total_price'] = $v['orderFee'];
            $order_info['total_price_usd'] = bcdiv($v['orderFee'],$rate,2);

            $order_info['created_at'] = $v['paidTime']; //订单生成时间
            $order_info['updated_at'] = $time;
            $order_info['processed_at'] = $v['paidTime']; //订单付款时间
            $order_info['total_weight'] = $v['orderWeight']; //重量
            $order_info['financial_status'] = 'paid'; //金融状态
            $order_info['orderStatus'] = $v['orderStatus']; //订单状态 	订单状态1.风控中 2.配货中 3.已发货 4.已完成 5.已作废
            $order_info['platformCode'] = $v['platformId']; //平台代码
            $order_info['gateway'] = $v['payType'];
            $order_info['addtime'] = $time; //新增时间
            $order_info['tracking_number'] = $v['trackNumber']; //运单号
            $order_info['tracking_type'] = $order_info['trackNumber']?1:0; //运单号
            $order_info['datetime'] = $v['paidTime']; //订单付款时间
            $order_info['transaction_id'] = $v['salesRecordNumber'];//交易号
            $order_info['api_type'] = 'mabang';
            $order_id = $this->order_data->save($order_info);

            if($order_id){

                foreach($v['orderItem'] as $k=>$item){
                    $order_goods_info = [];
                    $order_goods_info['o_id'] = $order_id;
                    $order_goods_info['product_id'] = $item['orderItemId'];
                    $order_goods_info['sku_id'] = $item['stockSku'];
                    $order_goods_info['shopify_o_id'] = $o_id;
                    $order_goods_info['quantity'] = $item['quantity'];
                    $order_goods_info['shop_id'] = $order_info['shop_id'];
                    $order_goods_info['user_id'] = $order_info['user_id'];
                    $order_goods_info['datetime'] = $order_info['datetime'];
                    $order_goods_info['shopify_goods_id'] = $item['orderItemId'];
                    $order_goods_info['webTransactionId'] = $item['transactionId'];
                    $order_goods_info['addtime'] = $time;
                    $order_goods_info['api_type'] = 'mabang';

                    $this->order_goods_data->store($order_goods_info,true);

                }
                $count++;
            }
        }
        return $count;
    }



}