<?php


class shopify_orders extends \Application\Component\Common\IFacade
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/order_synchro_data' );
        $this->load->model ( 'data/order_synchro_log_data' );
        $this->load->model ( 'data/order_data' );
        $this->load->model ( 'data/order_goods_data' );
    }


    public function index(){

        $shop_info = $this->order_synchro_data->get_shop_one();

        //没有店铺时 跳出程序
        if(empty($shop_info)){
            return false;
        }

        $time = time();
        $min_time = $shop_info['new_time']?str_replace(' ','T',date('Y-m-d H:i:s',$shop_info['new_time'])):'';
        $mix_time = str_replace(' ','T',date('Y-m-d H:i:s',$time));

        $url = 'https://'.$shop_info['key'].':'.$shop_info['password'].'@'.$shop_info['shop_url'].'/admin/api/2019-10/orders.json?order=updated_at&updated_at_min='.$min_time.'&updated_at_mix='.$mix_time;

        //修改请求日志表请求时间
        $this->order_synchro_data->edit_shop_time($shop_info['id'],$time);

        $this->get_order_page($shop_info['shop_url'],$url);

    }


    public function get_order_page($shop_url = '',$url = '',$page = 1){

        $order_list = curl_get_https($url.'&limit=250');
        $order_list = json_decode($order_list,true);

        $order_cout = count($order_list['orders']);
        if($order_cout>0){ //有订单时
            //添加同步日志
            $log_id = $this->order_synchro_log_data->add_log($shop_url,$url);
            //同步订单到本地
            $this->add_order($order_list['orders']);
            //修改订单同步状态
            $this->order_synchro_log_data->edit_log($log_id,1);

        }

//        if($order_cout>=10){
//            $page++;
//            $this->get_order_page($shop_url,$url,$page);
//        }
    }


    /**
     * 保存订单
     * @param array $arr
     */
    public function add_order($arr = []){

        foreach($arr as $v){
            $order_info = [];
            $order_info['shopify_o_id'] = $v['id'];
            $order_info['total_price_usd'] = $v['total_price_usd'];
            $order_info['created_at'] = $v['created_at'];
            $order_info['updated_at'] = $v['updated_at'];
            $order_info['total_weight'] = $v['total_weight'];
            $order_info['financial_status'] = $v['financial_status'];
            $order_id = $this->order_data->store($order_info,true);
            if($order_id){
                foreach($v['line_items'] as $item){
                    $order_goods_info = [];
                    $order_goods_info['product_id'] = $item['product_id'];
                    $order_goods_info['sku_id'] = $item['sku'];
                    $order_goods_info['o_id'] = $order_id;
                    $order_goods_info['quantity'] = $item['quantity'];
                    $this->order_goods_data->store($order_goods_info);
                }
            }
        }
    }

}