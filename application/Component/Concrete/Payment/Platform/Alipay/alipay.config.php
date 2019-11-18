<?php
/* *
 * 配置文件
 * 版本：3.5
 * 日期：2016-06-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */
//https://www.jiaoyitu.com/
$alipay_host = base_url ();
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
$alipay_config['partner']		= '2088621832231202';

//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
$alipay_config['seller_id']	= $alipay_config['partner'];

//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
$alipay_config['private_key']	= 'MIIEpgIBAAKCAQEAv9erqJRrvDq32hzvF7QRjS0uK2iWNeFFknDp0A4rTyP/3NXuPbX4651/qBqoFbBLBLtJMks/EwDGtNFWMGNKp6Ud2FVBdrcN1/+ntxFfn0hv74xOrQsM4jhvr9jCVJ4uh8c7dF3wHsnreE+sCuSOxL97NNqrCxWVnUq1yd/7U84lT1Oo+PUBW+B3Ag5dck2eScNtp7H0g5ixCxIYhT0p/DSfPq0SWPG/+ZefY9QeMejavQvyVnAaCpmUG173YrULtm59uZibBfTCbu9CVu/GGZK62jTs48JH8Y6M13/BHompZqFl/XRTSuprepbf6mJ9gA2fvIFgEUpHMt056XWGpwIDAQABAoIBAQC8QPBjOlpKWLiHobMB6KL8jaRr1nLopon1/TWEBBN90s7Gr2vdRM/irihSFu5wchH7r6lRYEOh6zYSxAW28AiXYFFcM7VMwK8mEetLmxHhpVyqjl26M0jOSSFYRvXTwXHZralrNK9oFy8pXc1u1wA+k8RchmQoOWMa+fBQDKp9feuthSqH4GbpWdlzkSPWR4xmY+DH6CYyW5mvXFCAnvfS/XRTigSzz/mrxckNBq1B1e0w2a1YKdDT7CidVvUbhLdItxISOEoWhBJBIR2i1MyDv0m46TgWrqtb/C+/JKXnjh3LdGIkRe6c1v+OQHiEc+m2ouiQOvLAI2Hg861e8pDpAoGBAPy9d3HM7gH868AHsGFqQWuVw8jNVmFvgChdAOzJG90q/Gig6QWfIYsHQGyVh30TyzXTFfvXbNhRtiX+EhF6GcDjxsNu2B06ASB+pwEBBpko2aRZ3OWn7pO2P8wpnRLNc8kt1wEQTR8cOsT7Fqsjq3to+iNDz7jS6j+7jPykG5rtAoGBAMJRH5iL8AKVlFX81jXtrJyg7h7UP2gwWvaCqXinQvh9v4K37uugmeKGMDdys5eGtPHigGbAVKc53JnmUj4I06WwBqrOW+QZr3DcO/Oyc4/LNMLTwDO4zwCxiPFlrcQP8B0s5EcwMe+m/LLKEPzO+h0Ic+OS6p7cK/pgV8P2PnFjAoGBAOyU4pFpNkYwHfJEgEu/7fsqVvnJlJliiUG/RVVhL68JRPsf3ODBQ+HjaN/73LctZyQ8MrDqx916J5pKyVkIxcC0tuNMCArbuCBVzjh3YFjQT2K0J73mQ8KLcA7JyVnHbiIcwc0iGD8N6slnDKIooqXD75pBKNmmvVXpVyFK8PhFAoGBAKSK2y1qed9GWCiEA8QAsWSkJV73rYWFRNFvDSB8ygnvOWbwZ6EhCo21wUiCmS7bQ4d7m+zmOgisx2+Oh9+9y7KGnu6t9UPuuA/ifeW7G34MvW2orx1dBfR5YRPAXOcj89hQtjDNz8s2ZM7kqoPYpBRHnxJ6yq+gkqDeShNHBdMvAoGBAIhyLnWbgTAHxjnJK3RXJdSZMrZfoG6gQVOYKV/jGtJpa5wL74HPcxHBUmjvskUpxRVnauXFz5LaMuc3cUGJv1LXtKUADB3GPanxKHfh7ULXS35dUs0fCX5e4L28rhLBm9xVgjqj+kWgbpmNDGbFnH3+V0tTOOjf/h3JBDQ00JdP';

//支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm 
$alipay_config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';

// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['notify_url'] = $alipay_host."payment_gateway/notify/alipay";

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$alipay_config['return_url'] = $alipay_host. "payment_gateway/back/alipay";

//签名方式
$alipay_config['sign_type']    = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = getcwd().'\\cacert.pem';

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

// 支付类型 ，无需修改
$alipay_config['payment_type'] = "1";
		
// 产品类型，无需修改
//移动端支付
if(IS_WAP){
	$alipay_config['service'] = "alipay.wap.create.direct.pay.by.user";

//PC端支付
}else{
	$alipay_config['service'] = "create_direct_pay_by_user";
}
//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	
// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
$alipay_config['anti_phishing_key'] = "";
	
// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
$alipay_config['exter_invoke_ip'] = "";
		
//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


$alipay_dev_config = array (
	'app_id'                    => '2017050307090189',
	'ali_dev_res' =>'RSA2',
	'ali_public_key'            => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgfBl/xDcmeA2RyihYjjj4bjc7EobFTmWd+NxjfZJoY4Ska/00ZXZquU1UTPsR9msF35KUXJGkQgh3M2tvOH+J8oYU9ymc3kuNwfC5iim+VA37hGg6x0JzrM7Yp/vyKCC5cK0mMakQEYXsyYnegaMk3EvRLlu869d880qc8QrQzROzo3mgfQ07hGS/4dKf37T8Ew2rQI6nXLnr7jNn4JmOAJXFnof55uziT7VSUVEgYG1UAfNWdft1S1rLDiUY/hR/4VD+w+IGgUs4HtSXRnxUHmLQb+XymZkbnWyUOzRsVrIP2RHG1n3IBCETiTvTPOh3sa1Ld4BU0EZ6/OK6V0FwQIDAQAB',
	// 可以填写文件路径，或者密钥字符串  我的沙箱模式，rsa与rsa2的私钥相同，为了方便测试
	//'rsa_private_key'           => 'MIIEpgIBAAKCAQEAv9erqJRrvDq32hzvF7QRjS0uK2iWNeFFknDp0A4rTyP/3NXuPbX4651/qBqoFbBLBLtJMks/EwDGtNFWMGNKp6Ud2FVBdrcN1/+ntxFfn0hv74xOrQsM4jhvr9jCVJ4uh8c7dF3wHsnreE+sCuSOxL97NNqrCxWVnUq1yd/7U84lT1Oo+PUBW+B3Ag5dck2eScNtp7H0g5ixCxIYhT0p/DSfPq0SWPG/+ZefY9QeMejavQvyVnAaCpmUG173YrULtm59uZibBfTCbu9CVu/GGZK62jTs48JH8Y6M13/BHompZqFl/XRTSuprepbf6mJ9gA2fvIFgEUpHMt056XWGpwIDAQABAoIBAQC8QPBjOlpKWLiHobMB6KL8jaRr1nLopon1/TWEBBN90s7Gr2vdRM/irihSFu5wchH7r6lRYEOh6zYSxAW28AiXYFFcM7VMwK8mEetLmxHhpVyqjl26M0jOSSFYRvXTwXHZralrNK9oFy8pXc1u1wA+k8RchmQoOWMa+fBQDKp9feuthSqH4GbpWdlzkSPWR4xmY+DH6CYyW5mvXFCAnvfS/XRTigSzz/mrxckNBq1B1e0w2a1YKdDT7CidVvUbhLdItxISOEoWhBJBIR2i1MyDv0m46TgWrqtb/C+/JKXnjh3LdGIkRe6c1v+OQHiEc+m2ouiQOvLAI2Hg861e8pDpAoGBAPy9d3HM7gH868AHsGFqQWuVw8jNVmFvgChdAOzJG90q/Gig6QWfIYsHQGyVh30TyzXTFfvXbNhRtiX+EhF6GcDjxsNu2B06ASB+pwEBBpko2aRZ3OWn7pO2P8wpnRLNc8kt1wEQTR8cOsT7Fqsjq3to+iNDz7jS6j+7jPykG5rtAoGBAMJRH5iL8AKVlFX81jXtrJyg7h7UP2gwWvaCqXinQvh9v4K37uugmeKGMDdys5eGtPHigGbAVKc53JnmUj4I06WwBqrOW+QZr3DcO/Oyc4/LNMLTwDO4zwCxiPFlrcQP8B0s5EcwMe+m/LLKEPzO+h0Ic+OS6p7cK/pgV8P2PnFjAoGBAOyU4pFpNkYwHfJEgEu/7fsqVvnJlJliiUG/RVVhL68JRPsf3ODBQ+HjaN/73LctZyQ8MrDqx916J5pKyVkIxcC0tuNMCArbuCBVzjh3YFjQT2K0J73mQ8KLcA7JyVnHbiIcwc0iGD8N6slnDKIooqXD75pBKNmmvVXpVyFK8PhFAoGBAKSK2y1qed9GWCiEA8QAsWSkJV73rYWFRNFvDSB8ygnvOWbwZ6EhCo21wUiCmS7bQ4d7m+zmOgisx2+Oh9+9y7KGnu6t9UPuuA/ifeW7G34MvW2orx1dBfR5YRPAXOcj89hQtjDNz8s2ZM7kqoPYpBRHnxJ6yq+gkqDeShNHBdMvAoGBAIhyLnWbgTAHxjnJK3RXJdSZMrZfoG6gQVOYKV/jGtJpa5wL74HPcxHBUmjvskUpxRVnauXFz5LaMuc3cUGJv1LXtKUADB3GPanxKHfh7ULXS35dUs0fCX5e4L28rhLBm9xVgjqj+kWgbpmNDGbFnH3+V0tTOOjf/h3JBDQ00JdP',
);

return array_merge ($alipay_config,$alipay_dev_config);
?>