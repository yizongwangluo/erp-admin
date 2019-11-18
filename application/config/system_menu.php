<?php
/**
 * 系统菜单
 * User: xiongbaoshan
 * Date: 2016/9/5
 * Time: 11:20
 */


$config = [
	['name' => '菜单管理', 'url' => site_url ( 'admin/menu/lists' ), 'disallow' => []],
	['name' => '游戏管理', 'url' => site_url ( 'admin/game/lists' ), 'disallow' => []],
	['name' => '平台管理', 'url' => site_url ( 'admin/supplier/lists' ), 'disallow' => []],
	['name' => '店铺管理', 'url' => site_url ( 'admin/shop/lists' ), 'disallow' => []],
	['name' => '商品管理', 'url' => site_url ( 'admin/goods/lists' ), 'disallow' => []],
	['name' => '订单管理', 'url' => site_url ( 'admin/order/lists' ), 'disallow' => ['editor1']],
	['name' => '渠道管理', 'url' => site_url ( 'admin/channel/lists' ), 'disallow' => []],
	['name' => '首充管理', 'url' => site_url ( 'admin/first_recharge/lists' ), 'disallow' => []],
	['name' => '续充管理', 'url' => site_url ( 'admin/continue_recharge/lists' ), 'disallow' => []],
	['name' => '代充管理', 'url' => site_url ( 'admin/proxy_recharge/lists' ), 'disallow' => []],
	['name' => '文章管理', 'url' => site_url ( 'admin/article/lists' ), 'disallow' => []],
	['name' => '用户管理', 'url' => site_url ( 'admin/user/lists' ), 'disallow' => ['editor1']],
	['name' => '商家管理', 'url' => site_url ( 'admin/seller/lists' ), 'disallow' => []],
	['name' => '广告管理', 'url' => site_url ( 'admin/ad/lists' ), 'disallow' => []],
	['name' => '推荐管理', 'url' => site_url ( 'admin/recommend/lists' ), 'disallow' => []],
	['name' => '表单管理', 'url' => site_url ( 'admin/form/lists' ), 'disallow' => []],
	['name' => '数据统计', 'url' => site_url ( 'admin/statistics/order_statistics_lists' ), 'disallow' => []],
	['name' => '权限账号', 'url' => site_url ( 'admin/privilege_account/lists' ), 'disallow' => []],
	['name' => '更新缓存', 'url' => site_url ( 'admin/cache' ), 'disallow' => []],
];