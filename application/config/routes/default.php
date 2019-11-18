<?php
/**
 * 默认路由配置
 * User: xiongbaoshan
 * Date: 2016/8/26
 * Time: 16:46
 */
$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['/']='home/index';
$route['games\.html']='games/game_library';
$route['game_xc']='continue_recharge';
//$route['game_xc/(\d*?)\.html']='continue_recharge/info/$1';
$route['game_xc/(\d*?)']='continue_recharge/info/$1';

$route['game_sch_sy/select_game']='first_recharge/select_game';

//$route['game_sch_sy/(\d*?)\.html']='first_recharge/info/$1';
$route['game_sch_sy/(\d*?)']='first_recharge/info/$1';
$route['game_sch_sy/(\w*?)']='first_recharge/lists/$1';


//$route['game_sch_yy/(\d*?)\.html']='first_recharge/info/$1';
$route['game_sch_yy/(\d*?)']='first_recharge/info/$1';
$route['game_sch_yy/(\w*?)']='first_recharge/lists/$1';

$route['game_sch_h5/(\d*?)']='first_recharge/info/$1';
$route['game_sch_h5/(\w*?)']='first_recharge/lists/$1';

$route['game_dc']='proxy_recharge/select_game';
//$route['game_dc/(\d*?)\.html']='proxy_recharge/info/$1';
$route['game_dc/(\d*?)']='proxy_recharge/info/$1';
$route['game_dc/(\w*?)']='proxy_recharge/lists/$1';

/* 店铺URL */
$route['shop/(\w*?)']='shop/detail/$1';

$route['kf'] = 'opening';

$route['sitemap\.xml'] = 'sitemap/index';