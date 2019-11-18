<?php
/**
 * 小师妹平台（http://www.xiaomiss.com/）
 * User: xiongbaoshan
 * Date: 2016/8/8
 * Time: 18:35
 */

namespace Application\Component\Concrete\FirstRecharge\Channel;

use Application\Component\Common\ILogic;
use Application\Component\Contract\FirstRecharge\Channel;
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
        $game_supplier=$this->game_supplier_data->get_info($goods['game_supplier_id']);
        $game_roles=explode(',',$order_ext['game_role_select']);
        $push_order=[
            'sender' => '265G',
            'actcode' => 'neworder',
            'order_type' => 'sc',
            'order_id' => $order['id'],
            'uid' => $order['buyer_id'],
            'item_id' => $order['goods_id'],
            'game_code' => $game['alias'],
            'os'=>$goods['device_type'],
            'agent'=>$game_supplier['alias'],
            'title' => iconv('utf-8',self::CHARSET,$order['order_content']),
            'recharge_value' => $goods_ext['recharge_value'],
            'recharge_unit' => iconv('utf-8',self::CHARSET,$goods_ext['currency_unit']),
            'trade_amount' => $order['order_amount'],
            'qu' => trim(iconv('utf-8',self::CHARSET,$order_ext['game_server'])),
            'zy' => trim(iconv('utf-8',self::CHARSET,$order_ext['game_sect'])),
            'rname' => trim(iconv('utf-8',self::CHARSET,$game_roles[0])),
            'rname1' => trim(iconv('utf-8',self::CHARSET,$game_roles[1])),
            'rname2' => trim(iconv('utf-8',self::CHARSET,$game_roles[2])),
            'rnamex' => $order_ext['game_role_exists'],
            'comm' => trim(iconv('utf-8',self::CHARSET,$order_ext['game_role_note'])),
            'tel' => $order_ext['mobile'],
            'qq' => $order_ext['qq'],
            'timestamp' => time()
        ];
        ksort($push_order);
        $sign_str=urldecode(http_build_query($push_order).'&'.self::KEY);
        log_message('xiaomiss_push',$sign_str,true);
        $sign=md5($sign_str);
        $push_order['sign']=$sign;
        log_message('xiaomiss_push',var_export($push_order,true),true);
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