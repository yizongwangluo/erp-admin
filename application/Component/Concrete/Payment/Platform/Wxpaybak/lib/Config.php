<?php
class Config{


    public function C($cfgName){
        $cfg_map=array(
            'url'=>'https://pay.swiftpass.cn/pay/gateway',	//支付请求url,无需更改
            'mchId'=>'7102650002',		//测试商户号,商户需更改为自己的
            'key'=>'86ca9563b6e4896fcaaba524a2f3b491',   //测试密钥,商户需更改为自己的
            'notify_url'=>base_url()."/payment_gateway/notify/wxpay",//测试通知url,商户需更改为自己的,保证能被外网访问到（否则支付成功后收不到威富通服务器所发通知）
            'version'=>'1.0'		//版本号
        );
        return $cfg_map[$cfgName];
    }
}
?>