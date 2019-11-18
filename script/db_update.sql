#对数据库结构的更改请将SQL写在这里
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID（来自265G用户中心）',
  `user_name` varchar(30) NOT NULL DEFAULT '' COMMENT '用户账号',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `register_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `register_ip` char(15) NOT NULL DEFAULT '' COMMENT '注册IP',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `login_ip` char(15) NOT NULL DEFAULT '' COMMENT '最后登陆IP',
  `money` double unsigned NOT NULL DEFAULT '0' COMMENT '账户余额',
  `seller_credit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '卖方信用',
  `buyer_credit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '买方信用',
  `unread_message` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '未读站内信',
  `bank_card_count` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '银行卡数量',
  `bind_mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '绑定手机',
  `bind_email` varchar(30) NOT NULL DEFAULT '' COMMENT '绑定邮箱',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `is_verified` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '实名认证',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE `user_cash_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '银行卡ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '（1银行卡，2支付宝）',
  `real_name` varchar(10) NOT NULL DEFAULT '' COMMENT '银行开户名',
  `card_number` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `idcard_number` varchar(30) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `idcard_imgs` varchar(500) NOT NULL DEFAULT '' COMMENT '身份证照片',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户现金账户表';

CREATE TABLE `user_identity_verify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '实名申请ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `real_name` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `idcard_number` varchar(30) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `idcard_imgs` varchar(500) NOT NULL DEFAULT '' COMMENT '身份证图片',
  `is_verify` tinyint(3) NOT NULL DEFAULT '0' COMMENT '（-1已拒绝，0未处理，1已通过）',
  `verify_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '认证通过时间',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注信息',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请认证时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户实名认证表';

CREATE TABLE `user_finance_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `operate` varchar(30) NOT NULL DEFAULT '' COMMENT '操作',
  `operate_remark` varchar(30) NOT NULL DEFAULT '' COMMENT '操作备注',
  `content` varchar(100) NOT NULL DEFAULT '' COMMENT '明细内容',
  `before_money` double unsigned NOT NULL DEFAULT '0' COMMENT '之前的金额',
  `money` double NOT NULL DEFAULT '0' COMMENT '资金变动',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发生时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`operate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户资金明细表';

CREATE TABLE `user_withdraw_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '提现ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `account_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '现金账户ID',
  `money` double unsigned NOT NULL DEFAULT '0' COMMENT '提现金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态（-1已失败，0未处理，1转账中，2已到账）',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `process_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '处理时间',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户提现申请表';

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `saler_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '卖家ID（用户ID、厂商ID等）',
  `buyer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买家ID',
  `order_number` varchar(30) NOT NULL DEFAULT '' COMMENT '订单号',
  `order_type` enum('recharge_money','game_props','game_account','first_recharge') NOT NULL DEFAULT 'first_recharge' COMMENT '订单类型（如首充号、余额充值、账号交易等）',
  `order_content` varchar(100) NOT NULL DEFAULT '' COMMENT '订单内容',
  `order_amount` double unsigned NOT NULL DEFAULT '0' COMMENT '订单金额',
  `order_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态（\r\n-3退货已成功，订单关闭\r\n-2发货已失败，订单关闭\r\n-1未支付成功，订单关闭；\r\n0下单成功；\r\n1支付成功；\r\n2发货成功；\r\n3收货成功；\r\n4交易成功\r\n）',
  `order_remark` varchar(100) NOT NULL DEFAULT '' COMMENT '订单备注',
  `payment_platform` varchar(100) NOT NULL DEFAULT '' COMMENT '支付平台',
  `paid_amount` double unsigned NOT NULL DEFAULT '0' COMMENT '已付款金额',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单创建时间',
  `payment_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单付款时间',
  `delivery_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '卖方发货时间',
  `receiving_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买方收货时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单结束时间（ 只要是订单结束了，不管成功还是失败，该字段始终有值 ）',
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

CREATE TABLE `order_ext_first_recharge` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `game_server` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏区服',
  `game_sect` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏职业',
  `game_role_select` varchar(30) NOT NULL DEFAULT '' COMMENT '角色名称预选',
  `game_role_exists` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '当角色存在（1随机生成，2联系询问）',
  `game_role_note` varchar(100) NOT NULL DEFAULT '' COMMENT '角色备注信息',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ',
  `game_account` varchar(30) NOT NULL DEFAULT '' COMMENT '平台创建的游戏账号',
  `game_role` varchar(30) NOT NULL DEFAULT '' COMMENT '平台创建的游戏角色',
  `game_password` varchar(30) NOT NULL DEFAULT '' COMMENT '平台创建的游戏密码',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单扩展表（首充号）';

CREATE TABLE `game_supplier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '运营商ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '运营商名称',
  `alias` varchar(30) NOT NULL DEFAULT '' COMMENT '运营商代码',
  `thumb_img` varchar(10) NOT NULL DEFAULT '' COMMENT '缩略图',
  `intro` text COMMENT '运营商介绍',
  `website` varchar(30) NOT NULL DEFAULT '' COMMENT '官方网站',
  `saler_credit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '卖方信用',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏运营商';

CREATE TABLE `game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '游戏ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '游戏类型（0页游，1手游）',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `alias` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏代码',
  `thumb_img` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `rebate_info` varchar(30) NOT NULL DEFAULT '' COMMENT '折扣信息',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='游戏表';

CREATE TABLE `game_server` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '服务器ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '服务器名称',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏服务器';

CREATE TABLE `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `number` varchar(30) NOT NULL DEFAULT '' COMMENT '商品编号',
  `type` enum('game_props','game_account','first_recharge') NOT NULL DEFAULT 'first_recharge' COMMENT '商品类型（如首充号、游戏装备、游戏账号等）',
  `saler_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `game_supplier_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏运营商ID',
  `game_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `game_server_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '游戏区服',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` varchar(100) NOT NULL DEFAULT '' COMMENT '子标题',
  `price` double unsigned NOT NULL DEFAULT '0' COMMENT '商品价格',
  `intro` text COMMENT '商品介绍',
  `thumb_img` varchar(100) NOT NULL DEFAULT '' COMMENT '封面缩略图',
  `content_img` varchar(100) NOT NULL DEFAULT '' COMMENT '内容页图片',
  `security_flag` varchar(30) NOT NULL DEFAULT '' COMMENT '安全保障标识（例如7天包退、保证金等）',
  `offline_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下架日期（指定时间未交易，自动下架）',
  `sale_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售量',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '商品状态（\r\n-2已下架；\r\n-1未通过；\r\n0待审核；\r\n1已审核；\r\n2已上线；\r\n）',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '状态备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

CREATE TABLE `goods_ext_first_recharge` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `original_price` double unsigned NOT NULL DEFAULT '0' COMMENT '原价',
  `exchange_rate` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '人民币兑游戏币汇率（1RMB=N游戏币）',
  `currency_unit` varchar(10) NOT NULL DEFAULT '' COMMENT '游戏货币单位（金币、元宝、金豆等）',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品扩展表（首充号）';

CREATE TABLE `user_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `to_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接收消息人',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '内容',
  `is_read` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '已经删除',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户站内信表';

CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `user_name` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_password` varchar(32) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `real_name` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `login_ip` char(15) NOT NULL DEFAULT '' COMMENT '登陆IP',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登陆时间',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_disable` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

CREATE TABLE `sms_send_log` (
  `send_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'verify_code' COMMENT '短信类型',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '目标手机',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '短信内容',
  `result` varchar(50) NOT NULL DEFAULT '' COMMENT '发送结果',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT '操作IP',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`send_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信发送日志表';

#删除多余字段
ALTER TABLE `order`
DROP COLUMN `paid_amount`;
ALTER TABLE `user_finance_detail`
DROP COLUMN `before_money`;
ALTER TABLE `user_finance_detail`
DROP COLUMN `operate_remark`;

#增加资金明细类型
ALTER TABLE `user_finance_detail`
ADD COLUMN `type`  varchar(30) NOT NULL DEFAULT '' COMMENT '日志类型' AFTER `operate`;

#游戏厂商增加通信秘钥字段
ALTER TABLE `game_supplier`
ADD COLUMN `key`  varchar(50) NOT NULL DEFAULT '' COMMENT '通信秘钥' AFTER `saler_credit`;

#平台编码
ALTER TABLE `game_supplier`
ADD COLUMN `charset`  varchar(30) NOT NULL DEFAULT '' COMMENT '平台编码' AFTER `key`;

#游戏区服修改为手动填写
ALTER TABLE `goods`
CHANGE COLUMN `game_server_id` `game_server`  varchar(30) NOT NULL DEFAULT '' COMMENT '游戏区服' AFTER `game_id`;

#订单状态优化
ALTER TABLE `order`
MODIFY COLUMN `order_status`  tinyint(4) NOT NULL DEFAULT 0 COMMENT '订单状态（\r\n-1交易取消；\r\n0等待付款；\r\n1等待发货；\r\n2正在发货；\r\n3等待收货；\r\n4收货成功\r\n5交易成功\r\n）' AFTER `order_amount`;

#商品表增加游戏类型字段
ALTER TABLE `goods`
ADD COLUMN `game_type`  tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0页游1手游' AFTER `game_id`;

#游戏运营商增加游戏列表字段
ALTER TABLE `game_supplier`
ADD COLUMN `game_list`  varchar(500) NOT NULL DEFAULT '' COMMENT '游戏列表' AFTER `saler_credit`;

#区分游戏运营商和商品渠道
ALTER TABLE `game_supplier`
DROP COLUMN `game_list`,
DROP COLUMN `key`,
DROP COLUMN `charset`;

#商品渠道商表
CREATE TABLE `goods_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '渠道商ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '渠道商名称',
  `alias` varchar(30) NOT NULL DEFAULT '' COMMENT '渠道商代码',
  `website` varchar(30) NOT NULL DEFAULT '' COMMENT '官方网站',
  `game_list` varchar(500) NOT NULL DEFAULT '' COMMENT '游戏列表',
  `key` varchar(50) NOT NULL DEFAULT '' COMMENT '通信秘钥',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='商品渠道商';

#商品表增加渠道商ID
ALTER TABLE `goods`
ADD COLUMN `channel_id`  int UNSIGNED NOT NULL DEFAULT 0 COMMENT '渠道商ID' AFTER `game_supplier_id`;

#商品增加设备类型字段
ALTER TABLE `goods`
ADD COLUMN `device_type`  varchar(30) NOT NULL DEFAULT '' COMMENT '设备类型' AFTER `game_type`;

#发货结果不需要角色
ALTER TABLE `order_ext_first_recharge`
DROP COLUMN `game_role`;

#渠道商表增加API接口标识符
ALTER TABLE `goods_channel`
ADD COLUMN `has_api`  tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '有系统接口（0没有，1有）' AFTER `game_list`;

#商品首充扩展数据表增加充值数量字段
ALTER TABLE `goods_ext_first_recharge`
ADD COLUMN `recharge_value`  int UNSIGNED NOT NULL DEFAULT 0 COMMENT '充值数量' AFTER `currency_unit`;

#游戏表增加推荐位标识
ALTER TABLE `game`
ADD COLUMN `pos_flag`  varchar(100) NOT NULL DEFAULT '' COMMENT '推荐位标识（详见enum_field.config文件）' AFTER `rebate_info`;

#商品表增加推荐位标识
ALTER TABLE `goods`
ADD COLUMN `pos_flag`  varchar(100) NOT NULL DEFAULT '' COMMENT '推荐位标识（详见enum_field.php）' AFTER `security_flag`;

#订单表字段名英文错误更正
ALTER TABLE `order`
CHANGE COLUMN `saler_id` `seller_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '卖家ID（用户ID、厂商ID等）' AFTER `id`;

#商品表增加缩略图2
ALTER TABLE `goods`
ADD COLUMN `thumb_img2`  varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图2' AFTER `thumb_img`;

#游戏表增加缩略图2
ALTER TABLE `game`
ADD COLUMN `thumb_img2`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '缩略图2' AFTER `thumb_img`;

#删除商品表缩略图2，因为加错了
ALTER TABLE `goods`
DROP COLUMN `thumb_img2`;

#文章表
CREATE TABLE `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `thumb_img` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `summary` varchar(300) NOT NULL DEFAULT '' COMMENT '内容摘要',
  `content` text COMMENT '内容',
  `pos_flag` varchar(100) NOT NULL DEFAULT '' COMMENT '推荐位标识（详见enum_field.php）',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序值 （倒序排列）',
  `is_show` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示（0隐藏，1显示）',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章数据表';

#栏目表
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `alias` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目代码',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序值（倒序）',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级栏目',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='栏目表';

#栏目增加可以行设置内容模板的字段
ALTER TABLE `category`
ADD COLUMN `info_tpl`  varchar(100) NOT NULL DEFAULT '' COMMENT '内容模板' AFTER `alias`;

#现金账户增加开户银行字段
ALTER TABLE `user_cash_account`
ADD COLUMN `bank_name`  tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '开户银行' AFTER `real_name`;

#账户金额字段优化
ALTER TABLE `user`
MODIFY COLUMN `money`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '账户余额' AFTER `login_ip`;

#广告位表
CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '' COMMENT '广告名称',
  `alias` varchar(50) NOT NULL DEFAULT '' COMMENT '广告别名',
  `type` enum('row','list') NOT NULL DEFAULT 'row',
  `content` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告管理表';

#用户现金账户增加删除状态
ALTER TABLE `user_cash_account`
ADD COLUMN `is_delete`  tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除状态（0未删除，1已删除）' AFTER `dateline`;

#游戏增加排序值
ALTER TABLE `game`
ADD COLUMN `sort`  int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序值' AFTER `pos_flag`;

#删除游戏汇率字段
ALTER TABLE `goods_ext_first_recharge`
DROP COLUMN `exchange_rate`;

#删除无用字段
ALTER TABLE `goods_channel`
DROP COLUMN `game_list`;

/***************************************************续充相关数据结构START***********************************************************************/
#订单表功能增强，为以后打折功能做准备
ALTER TABLE `order`
MODIFY COLUMN `order_amount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单总额' AFTER `order_content`,
ADD COLUMN `pay_amount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '付款金额' AFTER `order_amount`,
ADD COLUMN `discount_amount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '折扣金额' AFTER `pay_amount`,
ADD COLUMN `goods_price`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品单价' AFTER `goods_id`,
ADD COLUMN `goods_quantity`  int UNSIGNED NOT NULL DEFAULT 1 COMMENT '商品数量' AFTER `goods_price`;
update `order` as o set goods_price=(select price from goods where id=o.goods_id) where o.order_type='first_recharge';
update `order` set pay_amount=order_amount;

#商品类型增加续充
ALTER TABLE `goods`
MODIFY COLUMN `type`  enum('game_props','game_account','continue_recharge','first_recharge') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'first_recharge' COMMENT '商品类型（首充号、续充、游戏装备、游戏账号等）' AFTER `number`;
ALTER TABLE `order`
MODIFY COLUMN `order_type`  enum('recharge_money','game_props','game_account','continue_recharge','first_recharge') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'first_recharge' COMMENT '订单类型（首充号、续充、余额充值、账号交易等）' AFTER `order_number`;

#续充商品表
CREATE TABLE `goods_ext_continue_recharge` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `currency_unit` varchar(10) NOT NULL DEFAULT '' COMMENT '游戏货币单位（金币、元宝、金豆等）',
  `recharge_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值数量',
  `rebate_info` varchar(30) NOT NULL DEFAULT '' COMMENT '折扣信息',
  `price_rules` text COMMENT '计价规则',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='续充商品表';

#续充订单表
CREATE TABLE `order_ext_continue_recharge` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `game_account` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏账号',
  `game_password` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏密码',
  `game_server` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏区服',
  `game_role` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏角色',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ',
  `note` varchar(100) NOT NULL DEFAULT '' COMMENT '其他备注信息',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单扩展表（续充）';
/***************************************************续充相关数据结构END***********************************************************************/

#商品增加关键词字段
ALTER TABLE `goods`
ADD COLUMN `keywords`  varchar(100) NOT NULL DEFAULT '' COMMENT '关键词' AFTER `subtitle`;
update `goods` as g set keywords=(select name from game where id=g.game_id) where g.type='first_recharge';

#银行卡绑定弃用身份证照片
ALTER TABLE `user_cash_account`
MODIFY COLUMN `idcard_imgs`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '身份证照片（已弃用）' AFTER `idcard_number`;


CREATE TABLE `discount_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '活动名称',
  `intro` text COMMENT '详细介绍',
  `rules` varchar(100) NOT NULL DEFAULT '' COMMENT '折扣规则',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='打折活动表';

CREATE TABLE `order_discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '打折ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `type` enum('coupon','activity') NOT NULL DEFAULT 'coupon' COMMENT '减免方式（代金券、优惠活动）',
  `flag` varchar(50) NOT NULL DEFAULT '' COMMENT '减免标识（代金券ID、优惠活动代码等信息）',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '减免金额',
  `note` varchar(100) NOT NULL DEFAULT '' COMMENT '备注信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='订单打折明细表';

CREATE TABLE `user_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '代金券ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源代码（必需是英文）',
  `condition` varchar(100) NOT NULL DEFAULT '' COMMENT '使用条件',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '减免金额',
  `is_used` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用',
  `use_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获得时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户代金券表';

#添加M端广告位
INSERT INTO `ad` (`name`, `alias`, `type`) VALUES ('M端首页幻灯片', 'wap_index_slide', 'list');


CREATE TABLE `user_invite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '邀请ID',
  `inviter_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请人',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被邀请人(也就是当前登录的uid)',
  `level` smallint(8) NOT NULL COMMENT '被邀请人级别',
  `parents` varchar(10000) NOT NULL COMMENT '父级id集合',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源代码（必需是英文）',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请时间(被邀请人注册时间)',
  `note` varchar(100) NOT NULL DEFAULT '' COMMENT '备注信息',
  `ip` char(15) NOT NULL COMMENT 'IP地址',
  PRIMARY KEY (`id`),
  KEY `inviter_id` (`inviter_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户邀请表';

#增加管理员editor1（密码：editor1,#147）
INSERT INTO `admin` (`id`, `user_name`, `user_password`, `real_name`, `role_id`, `login_ip`, `login_time`, `dateline`, `is_disable`) VALUES ('2', 'editor1', '80fdc234cb6dc84fdc2f4a71c9545b3b', '编辑1', '0', '', '0', '0', '0');

#代金券增加字段：使用限制
ALTER TABLE `user_coupon`
ADD COLUMN `limits`  varchar(50) NOT NULL DEFAULT '' COMMENT '使用限制' AFTER `user_id`;

#打折活动增加字段：限制品类
ALTER TABLE `discount_activity`
ADD COLUMN `limits`  varchar(50) NOT NULL DEFAULT '' COMMENT '限制品类' AFTER `rules`;



#*****************************************************代充功能START************************************************************************

#短信模板增加：您的订单xxxxxxxxxx已经发货啦！赶紧去“我的订单”中看看吧

ALTER TABLE `goods`
MODIFY COLUMN `type`  enum('game_props','game_account','continue_recharge','proxy_recharge','first_recharge') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'first_recharge' COMMENT '商品类型（首充号、代充、续充、游戏装备、游戏账号等）' AFTER `number`;

ALTER TABLE `order`
MODIFY COLUMN `order_type`  enum('recharge_money','game_props','game_account','continue_recharge','proxy_recharge','first_recharge') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'first_recharge' COMMENT '订单类型（首充号、代充、续充、余额充值、账号交易等）' AFTER `order_number`;

CREATE TABLE `goods_ext_proxy_recharge` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `original_price` double unsigned NOT NULL DEFAULT '0' COMMENT '原价',
  `currency_unit` varchar(10) NOT NULL DEFAULT '' COMMENT '游戏货币单位（金币、元宝、金豆等）',
  `recharge_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值数量',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品扩展表（代充）';

CREATE TABLE `order_ext_proxy_recharge` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `game_account` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏账号',
  `game_password` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏密码',
  `game_server` varchar(30) NOT NULL DEFAULT '' COMMENT '游戏区服',
  `login_type` varchar(30) NOT NULL DEFAULT '' COMMENT '登陆方式',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ',
  `note` varchar(100) NOT NULL DEFAULT '' COMMENT '其他备注',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单扩展表（代充）';

INSERT INTO `game_supplier` (`name`, `alias`) VALUES ('IOS', 'ios');

ALTER TABLE `game`
MODIFY COLUMN `rebate_info`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '首充折扣信息' AFTER `thumb_img2`,
ADD COLUMN `proxy_recharge_rebate_info`  varchar(30) NOT NULL DEFAULT '' COMMENT '代充折扣信息' AFTER `rebate_info`;

ALTER TABLE `discount_activity`
ADD COLUMN `alias`  varchar(100) NOT NULL DEFAULT '' COMMENT '活动代码' AFTER `name`,
ADD UNIQUE INDEX `alias` (`alias`) ;

ALTER TABLE `discount_activity`
DROP COLUMN `rules`,
DROP COLUMN `limits`;

INSERT INTO `ad` (`name`, `alias`, `type`) VALUES ('M端限时特惠幻灯', 'wap_first_activity_slide', 'list');

INSERT INTO `game_supplier` (`name`, `alias`) VALUES ('360', '360');
INSERT INTO `goods_channel` (`name`, `alias`, `website`, `has_api`) VALUES ('360', '360', 'http://wan.360.cn/', '0');

INSERT INTO `game_supplier` (`name`, `alias`) VALUES ('九游-UC', 'jyuc');
INSERT INTO `goods_channel` (`name`, `alias`, `website`, `has_api`) VALUES ('九游-UC', 'jyuc', 'http://www.9game.cn/', '0');

ALTER TABLE `order`
ADD COLUMN `from_device`  enum('WAP','PC') NOT NULL DEFAULT 'PC' COMMENT '下单设备' AFTER `end_time`;
update `order` set from_device='WAP' where discount_amount>0 and pay_amount=0.1;

INSERT INTO `game_supplier` (`name`, `alias`) VALUES ('豌豆荚', 'wdj');
INSERT INTO `goods_channel` (`name`, `alias`, `website`, `has_api`) VALUES ('豌豆荚', 'wdj', 'https://www.wandoujia.com/', '0');

ALTER TABLE game
ADD COLUMN continue_recharge_rebate_info VARCHAR(30) NOT NULL DEFAULT  '' COMMENT '续充折扣信息';

ALTER TABLE goods
ADD COLUMN intro2 text COMMENT '商品介绍2';

CREATE TABLE user_collect_goods(
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL COMMENT '用户id',
  goods_id INT NOT NULL COMMENT'商品id',
  time VARCHAR(11) NOT NULL COMMENT'收藏时间'
)COMMENT='用户收藏商品表';

alter table ad MODIFY type ENUM('row','list','mixed') not null DEFAULT 'row';
INSERT INTO ad (name,alias,type,content) values('个人中心收藏推荐商品', 'recommend', 'mixed', '');
ALTER TABLE user_cash_account ADD bank_address VARCHAR(80) NOT NULL DEFAULT '' comment '开户行地址';

ALTER TABLE `goods`
  ADD COLUMN `server_limit`  tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '区服限制（0不限，1限制）' AFTER `game_server`;

CREATE TABLE `hd_huoying_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `prize_name` varchar(50) DEFAULT NULL COMMENT '奖品名称',
  `user_name` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `time` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1851 DEFAULT CHARSET=utf8;

CREATE TABLE `hd_huoying_user_info` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(50) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_share_time` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户抽奖次数表';

ALTER table hd_huoying_record add ip VARCHAR(15) not NULL  DEFAULT '';

INSERT INTO `game_supplier` (`name`, `alias`) VALUES ('72G手游', '72g');
INSERT INTO `goods_channel` (`name`, `alias`, `website`, `has_api`) VALUES ('72G手游', '72g', 'http://www.72g.com', '0');

INSERT INTO `game_supplier` (`name`, `alias`) VALUES ('当乐', 'dcn');
INSERT INTO `goods_channel` (`name`, `alias`, `website`, `has_api`) VALUES ('当乐', 'dcn', 'http://www.d.cn', '0');


ALTER TABLE `recommend`
MODIFY COLUMN `flag`  tinyint(1) NULL DEFAULT NULL COMMENT '推荐标签 1.首页热门手游 2.首页热门网游 3.首页店铺推荐 4.265g-游戏交易-页游 5.265g首页-游戏交易-手游\r\n6.M端首页-热门手游\r\n7.M端首页-热门页游\r\n8.M端首页-店铺推荐' AFTER `id`;

INSERT INTO `goods_channel` (`name`, `alias`, `website`, `has_api`) VALUES ('当乐', 'dcn', 'http://www.d.cn', '0');
ALTER TABLE `game`ADD COLUMN `ratio`  float(4,2) NULL COMMENT '内部结算比例' AFTER `dateline`;