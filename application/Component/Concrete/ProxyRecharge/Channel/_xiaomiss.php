<?php
/**
 * 小师妹平台（http://www.xiaomiss.com/）
 * User: xiongbaoshan
 * Date: 2016/8/8
 * Time: 18:35
 */

namespace Application\Component\Concrete\ProxyRecharge\Channel;

use Application\Component\Common\ILogic;
use Application\Component\Contract\ProxyRecharge\Channel;
use Application\Component\Traits\ErrorReporter;

class _xiaomiss extends ILogic implements Channel
{
    use ErrorReporter;

    const KEY='xiaomiss265g';
    const CHARSET='gbk';
    const API_URL='http://www.xiaomiss.com/front/fh_order_itfc';

    function __construct()
    {
        parent::__construct();
        $this->load->model('data/order_data');
        $this->load->model('data/goods_data');
        $this->load->model('data/game_data');
        $this->load->model('data/game_supplier_data');

    }

    public function delivery(array $order)
    {
        $order_ext=$this->order_data->get_ext_info($order['order_type'],$order['id']);
        $goods=$this->goods_data->get_info($order['goods_id']);
        $goods_ext=$this->goods_data->get_ext_info($goods['type'],$goods['id']);
        $game=$this->game_data->get_info($goods['game_id']);
        $push_order=[
            'sender' => '265G',
            'actcode' => 'neworder',
            'order_type' => 'dc',
            'uid' => $order['buyer_id'],
            'item_id' => $order['goods_id'],
            'game_code' => $game['alias'],
            'order_id' => $order['id'],
            'title' => iconv('utf-8',self::CHARSET,$order['order_content']),
            'recharge_value' => $goods_ext['recharge_value'],
            'recharge_unit' => iconv('utf-8',self::CHARSET,$goods_ext['currency_unit']),
            'trade_amount' => $order['order_amount'],
            'buy_num' => $order['goods_quantity'],
            'game_login_type'=>$order_ext['login_type'],
            'game_account' => iconv('utf-8',self::CHARSET,$order_ext['game_account']),
            'game_password' => iconv('utf-8',self::CHARSET,$order_ext['game_password']),
            'qu' => iconv('utf-8',self::CHARSET,$order_ext['game_server']),
            'comm' => iconv('utf-8',self::CHARSET,$order_ext['note']),
            'tel' => $order_ext['mobile'],
            'qq' => $order_ext['qq'],
            'timestamp' => time()
        ];
        log_message('xiaomiss_dc_push',var_export($push_order,true),true);
        ksort($push_order);
        $sign_str=urldecode(http_build_query($push_order).'&'.self::KEY);
        $sign=md5($sign_str);
        $push_order['sign']=$sign;
        $result_raw=curl_post(self::API_URL,$push_order);
        if(!$result_raw){
            $this->set_error('对方服务器无响应');
            return false;
        }
        $result=json_decode($result_raw,true);
        if(!$result){
            $this->set_error('解析响应数据失败');
            return false;
        }
        if(!$result['status']){
            $this->set_error(iconv(self::CHARSET,'utf-8',$result['msg']));
            return false;
        }

        return true;
    }


}