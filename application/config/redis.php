<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/5 0005
 * Time: 17:48
 */
// cache_redis
$config['default']= array (
	'host' =>'127.0.0.1',
//	'password' =>'111111',
	'port'=>6379,
	'timeout'=>5,
	//当主的挂掉，采用备用的
	'failover'=>array (
		array (
			'host' =>'127.0.0.1',
//			'password' =>'111111',
			'port'=>6379,
			'timeout'=>5,
		),
		array (
			'host' =>'127.0.0.1',
//			'password' =>'111111',
			'port'=>6379,
			'timeout'=>5,
		)
	)
);