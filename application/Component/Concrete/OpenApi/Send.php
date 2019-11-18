<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/17 0017
 * Time: 14:07
 */

namespace Application\Component\Concrete\OpenApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Send implements \Application\Component\Contract\OpenApi\Send
{
	//应用的App_id
	protected $app_id;
	//应用的密钥
	protected $app_sk;
	//应用的URL
	protected $app_url;

	public function __construct ( $appInfo )
	{
		$this->app_id = $appInfo['user_id'];
		$this->app_sk = $appInfo['app_secret'];
		$this->app_url = $appInfo['app_url'];
	}

	public function fire ( $info, \Closure $callback )
	{
		$sign = Tools::encrypt ( $info['order_number'], $this->app_id, $this->app_sk );
		$data = [
			'message' => $info,
			'type' => 'jyt.order.continue_recharge',
			'sign' => $sign
		];
		$client = new  Client( ['timeout' => 3.14] );
		try {
			$r = $client->request ( 'POST', $this->app_url, [
				'verify' => false,
				'form_params' => $data,
				'headers' => [
					'User-Agent' => 'JiaoyituApi Send/1.0',
					'Accept' => 'application/json'
				]
			] );
			$response_code = $r->getStatusCode ();
			if ( $response_code != 200 ) {
				$callback();
				return \false;
			}
			$respone_body = (string)$r->getBody ();
			$result = json_decode ( $respone_body, \true );
			if ( $result['status'] != 'success' ) {
				$callback();
				return \false;
			}
		} catch ( RequestException $e ) {
			$callback();
			return \false;
		}
		return \true;
	}

}