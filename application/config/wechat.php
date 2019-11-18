<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/14 0014
 * Time: 10:57
 */

$config['wechat'] = [
	/**
	 * Debug 模式，bool 值：true/false
	 *
	 * 当值为 false 时，所有的日志都不会记录
	 */
	'debug' => false,
	/**
	 * 账号基本信息，请从微信公众平台/开放平台获取
	 */
	'app_id'  => 'wx26a3a07f61199469',         // AppID
//	'app_id' => 'wxc9780d6b23e40640',
	'secret' => '863d5ff654d9d2d6ca5b391a72976745',     // AppSecret
//	'secret' => '9f1646ffdb394a8376c1b025de047997',
	'token' => 'jiaoyitu',          // Token
//	'token' => 'weixin',
	'aes_key' => '33sreUEDxl97UlW31u7iqsTpa82UnU21QrbHlJYwUab',                    // EncodingAESKey，安全模式下请一定要填写！！！
	/**
	 * 日志配置
	 *
	 * level: 日志级别, 可选为：
	 *         debug/info/notice/warning/error/critical/alert/emergency
	 * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
	 * file：日志文件位置(绝对路径!!!)，要求可写权限
	 */
	'log' => [
		'level' => 'error',
		'permission' => 0777,
		'file' => APPPATH . '/logs/wechat/wechat_' . date ( 'Y_m_d' ) . '.log',
	],
	/**
	 * OAuth 配置
	 *
	 * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
	 * callback：OAuth授权完成后的回调页地址
	 */
	'oauth' => [
		'scopes' => ['snsapi_userinfo'],
		'callback' => base_url () . 'weixin/oauth/callback',
	],
	/**
	 * 微信支付
	 */
	'payment' => [
		'merchant_id' => '1482928702',
		'key' => '4LEuEEe32fYzDgJWvCoUT3RbUt3JFFhS',
		'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
		'key_path' => 'path/to/your/key',      // XXX: 绝对路径！！！！
		// 'device_info'     => '013467007045764',
		// 'sub_app_id'      => '',
		// 'sub_merchant_id' => '',
		// ...
	],
	/**
	 * Guzzle 全局设置
	 *
	 * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
	 */
	'guzzle' => [
		'timeout' => 5.0, // 超时时间（秒）
		//'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
	],
];