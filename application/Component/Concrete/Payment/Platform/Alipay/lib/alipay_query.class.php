<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/8 0008
 * Time: 18:13
 */
require_once("alipay_core.function.php");
require_once("alipay_rsa.function.php");
class AliQuery{
	var $alipay_config;
	var $signType;
	var $version ='1.0';
	var $format = 'JSON';
	var $charset ='UTF-8';
	var $alipay_gateway_new = 'https://openapi.alipay.com/gateway.do?';
	var $WaitingSignData;
	var $method = 'alipay.trade.query';
	function __construct($alipay_config){
		$this->alipay_config = $alipay_config;
		$this->signType = strtoupper(trim($alipay_config['ali_dev_res']));
	}
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	function createSign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	    $this->WaitingSignData = $prestr = createLinkstring($para_sort);
		$mysign = "";
		switch (strtoupper(trim($this->alipay_config['ali_dev_res']))) {
			case "RSA" :
				$mysign = rsaSign($prestr, $this->alipay_config['private_key']);
				break;
			case "RSA2" :
				$mysign = rsaSign($prestr, $this->alipay_config['private_key'],'rsa2');
				break;
			default :
				$mysign = "";
		}

		return $mysign;
	}

	function buildRequestPara($para_temp){
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);
		$para_filter['sign_type'] = $this->signType;
		//对待签名参数数组排序
		$para_sort = argSort($para_filter);
		//生成签名结果
		$mysign = $this->createSign($para_sort);
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;

		return $para_sort;

	}
	function buildRequestUrl($parameter){
		$signData = [
			// 公共参数
			'app_id'        => $this->alipay_config['app_id'],
			'method'        => $this->method,
			'format'        => $this->format,
			'charset'       => $this->charset,
			'sign_type'     => $this->signType,
			'timestamp'     => date ('Y-m-d H:i:s'),
			'version'       => $this->version,
			// 业务参数
			'biz_content'   => $this->getBizContent($parameter),
		];
		$signData = $this->buildRequestPara ($signData);
		$sign = $signData['sign'];
		unset($signData['sign']);
		$signData = argSort($signData);
		//签名要放在最后面
		$signData['sign'] =$sign;
		$para_temp =  http_build_query($signData);
		return $this->dejson ($this->alipay_gateway_new.$para_temp);
	}
	protected function dejson($url){
		$ret = curl_get($url);
		$d = json_decode($ret, JSON_UNESCAPED_UNICODE);
		$method= str_replace ('.','_',$this->method).'_response';
		if ($d[$method]['code'] =='10000'){
			$flag = $this->getRsaVerify ($d[$method],$d['sign']);
			if (!$flag){
				throw  new  Exception('支付签名校验失败！');
				exit();
			}
			return $d[$method];
		}else{
			return '';
		}
	}

	protected  function  getRsaVerify($para_temp,$sign){
		$prestr = json_encode ($para_temp);
		$isSgin = false;
		switch ($this->signType) {
			case "RSA2" :
				$isSgin = rsaVerify($prestr, trim($this->alipay_config['ali_public_key']), $sign,'RSA2');
				break;
			default :
				$isSgin = false;
		}
		return $isSgin;
	}


	protected function getBizContent($content)
	{
		// 二者不能同时为空
		if (empty($content['out_trade_no']) && empty($content['trade_no'])) {
			throw new PayException('必须提供支付宝交易号或者商户网站唯一订单号。建议使用支付宝交易号');
		}
		$content = paraFilter ($content);// 过滤掉空值，下面不用在检查是否为空
		return json_encode($content, JSON_UNESCAPED_UNICODE);
	}

}