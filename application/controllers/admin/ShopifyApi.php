<?php
/**
 * shopify api接口
 * User: xiongbaoshan
 * Date: 2016/7/13
 * Time: 9:46
 */

set_time_limit ( 0 );

class ShopifyApi extends MY_Controller
{

    public function ceshi(){

        $shoplist = model('data/shop_data')->lists(['status'=>1]);

        foreach($shoplist as $value){

            if(empty(trim($value['shop_api_key'])) || empty(trim($value['shop_api_pwd'])) || empty(rtrim($value['backstage']))){

                log_message('update_shop','域名='.$value['domain'].'|||关闭原因：信息不完整',true);

                model('data/shop_data')->update($value['id'],['status'=>0]);
                continue;
            }

            if(empty($value['timezone'])){

                $url = 'https://'.$value['shop_api_key'].':'.$value ['shop_api_pwd'].'@'.$value['backstage'].'/api/2019-10/shop.json';
                $json = curl_get_https($url);

                log_message('shopifyapi_ceshi','shop_id.='.$value['id'].'|||'.$json,true);

                $json = json_decode($json,true);

                if($json['shop']){
                    $timezone = [];
                    preg_match('/\(GMT(.*?\))/',$json['shop']['timezone'],$timezone);

                    model('data/shop_data')->update($value['id'],['timezone'=>rtrim($timezone[1],')')]);
                }else{

                    log_message('update_shop','域名='.$value['domain'].'|||关闭原因：接口校验失败'.$json['errors'],true);

                    model('data/shop_data')->update($value['id'],['status'=>0]);
                }
            }
        }
    }
    /**
     * 获取店铺信息
     * @return string
     */
    public function getShopInfo(){

        $data = $this->input->post();

        if(empty($data['shop_api_key']) || empty($data['shop_api_pwd']) || empty($data['backstage'])){
            echo json_encode(['code'=>0,'msg'=>'请填写《后台》、《店铺API密钥》、《店铺API密码》，再进行校验']);exit;
        }

        $url = 'https://'.$data['shop_api_key'].':'.$data['shop_api_pwd'].'@'.$data['backstage'].'/api/2019-10/shop.json';

        $json = curl_get_https($url);

        $json = json_decode($json,true);

        if($json['shop']){

            $timezone = [];

            preg_match("/\(GMT(.*?\))/i",$json['shop']['timezone'], $timezone);

            $json['shop']['timezone'] = rtrim($timezone[1],')') ;

            echo  json_encode(['code'=>1,'msg'=>'ok','data'=>$json['shop']]);
        }else{
            echo json_encode(['code'=>0,'msg'=>$json['errors']]);
        }
    }

}