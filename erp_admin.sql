/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : erp_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-11-18 16:02:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `user_name` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_password` varchar(32) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `real_name` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `role_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '角色ID',
  `login_ip` char(15) NOT NULL DEFAULT '' COMMENT '登陆IP',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登陆时间',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_disable` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员列表';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '管理员', '1', '127.0.0.1', '1574063971', '0', '0');

-- ----------------------------
-- Table structure for admin_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_auth_group`;
CREATE TABLE `admin_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限列表';

-- ----------------------------
-- Records of admin_auth_group
-- ----------------------------
INSERT INTO `admin_auth_group` VALUES ('1', '超级管理员', '1', '24,1,3,4,5,6,7,8,9,10,11,12,13,14,15,17,16,25,18,23,19,21,22,26,27,28,29,30,32,33');

-- ----------------------------
-- Table structure for admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `url` varchar(200) DEFAULT NULL COMMENT 'URL地址',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `ua` varchar(200) DEFAULT NULL COMMENT 'ua请求头',
  `ip` char(50) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL COMMENT '时间',
  `request` char(20) DEFAULT NULL COMMENT '请求方式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台操作日志表';

-- ----------------------------
-- Records of admin_logs
-- ----------------------------
INSERT INTO `admin_logs` VALUES ('39', '1', 'admin', 'admin/admin_logs/lists', '操作日志', '[]', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36', '192.168.0.93', '1546935751', 'GET');
INSERT INTO `admin_logs` VALUES ('40', '1', 'admin', 'admin/admin_logs/lists', '操作日志', '[]', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36', '192.168.0.93', '1546936894', 'GET');
INSERT INTO `admin_logs` VALUES ('41', '1', 'admin', 'admin/admin_logs/lists', '操作日志', '[]', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36', '192.168.0.93', '1546936895', 'GET');
INSERT INTO `admin_logs` VALUES ('42', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '[]', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36', '192.168.0.93', '1546936897', 'GET');
INSERT INTO `admin_logs` VALUES ('43', '1', 'admin', 'admin/admin_logs/lists', '操作日志', '[]', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36', '192.168.0.93', '1546936898', 'GET');
INSERT INTO `admin_logs` VALUES ('44', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063786', 'GET');
INSERT INTO `admin_logs` VALUES ('45', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063800', 'GET');
INSERT INTO `admin_logs` VALUES ('46', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063801', 'GET');
INSERT INTO `admin_logs` VALUES ('47', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063801', 'GET');
INSERT INTO `admin_logs` VALUES ('48', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063804', 'GET');
INSERT INTO `admin_logs` VALUES ('49', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063804', 'GET');
INSERT INTO `admin_logs` VALUES ('50', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063807', 'GET');
INSERT INTO `admin_logs` VALUES ('51', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063808', 'GET');
INSERT INTO `admin_logs` VALUES ('52', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063811', 'GET');
INSERT INTO `admin_logs` VALUES ('53', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063811', 'GET');
INSERT INTO `admin_logs` VALUES ('54', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063814', 'GET');
INSERT INTO `admin_logs` VALUES ('55', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063814', 'GET');
INSERT INTO `admin_logs` VALUES ('56', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063817', 'GET');
INSERT INTO `admin_logs` VALUES ('57', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063817', 'GET');
INSERT INTO `admin_logs` VALUES ('58', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063821', 'GET');
INSERT INTO `admin_logs` VALUES ('59', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063821', 'GET');
INSERT INTO `admin_logs` VALUES ('60', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063824', 'GET');
INSERT INTO `admin_logs` VALUES ('61', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063824', 'GET');
INSERT INTO `admin_logs` VALUES ('62', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063827', 'GET');
INSERT INTO `admin_logs` VALUES ('63', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063827', 'GET');
INSERT INTO `admin_logs` VALUES ('64', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063830', 'GET');
INSERT INTO `admin_logs` VALUES ('65', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063831', 'GET');
INSERT INTO `admin_logs` VALUES ('66', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063833', 'GET');
INSERT INTO `admin_logs` VALUES ('67', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063833', 'GET');
INSERT INTO `admin_logs` VALUES ('68', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063836', 'GET');
INSERT INTO `admin_logs` VALUES ('69', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063836', 'GET');
INSERT INTO `admin_logs` VALUES ('70', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063839', 'GET');
INSERT INTO `admin_logs` VALUES ('71', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063840', 'GET');
INSERT INTO `admin_logs` VALUES ('72', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063843', 'GET');
INSERT INTO `admin_logs` VALUES ('73', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063843', 'GET');
INSERT INTO `admin_logs` VALUES ('74', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063846', 'GET');
INSERT INTO `admin_logs` VALUES ('75', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063846', 'GET');
INSERT INTO `admin_logs` VALUES ('76', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063847', 'GET');
INSERT INTO `admin_logs` VALUES ('77', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063847', 'GET');
INSERT INTO `admin_logs` VALUES ('78', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063850', 'GET');
INSERT INTO `admin_logs` VALUES ('79', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063850', 'GET');
INSERT INTO `admin_logs` VALUES ('80', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063853', 'GET');
INSERT INTO `admin_logs` VALUES ('81', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063853', 'GET');
INSERT INTO `admin_logs` VALUES ('82', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063856', 'GET');
INSERT INTO `admin_logs` VALUES ('83', '1', 'admin', 'admin/cache/redis', 'redis缓存清空', '{\"\\/admin\\/cache\\/redis\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063856', 'GET');
INSERT INTO `admin_logs` VALUES ('84', '1', 'admin', 'admin/admin_user/lists', '管理员列表', '{\"\\/admin\\/admin_user\\/lists\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063859', 'GET');
INSERT INTO `admin_logs` VALUES ('85', '1', 'admin', 'admin/menu/lists', '菜单管理', '{\"\\/admin\\/menu\\/lists\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063864', 'GET');
INSERT INTO `admin_logs` VALUES ('86', '1', 'admin', 'admin/menu/edit/1', null, '{\"\\/admin\\/menu\\/edit\\/1\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063960', 'GET');
INSERT INTO `admin_logs` VALUES ('87', '1', 'admin', 'admin/menu/save', null, '{\"\\/admin\\/menu\\/save\":\"\",\"id\":\"1\",\"pid\":\"\",\"name\":\"\\u66f4\\u65b0\\u7f13\\u5b58\",\"url\":\"\",\"status\":\"0\",\"sort\":\"1\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063961', 'POST');
INSERT INTO `admin_logs` VALUES ('88', '1', 'admin', 'admin/menu/lists', '菜单管理', '{\"\\/admin\\/menu\\/lists\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063964', 'GET');
INSERT INTO `admin_logs` VALUES ('89', '1', 'admin', 'admin/login/logout', '退出后台', '{\"\\/admin\\/login\\/logout\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063967', 'GET');
INSERT INTO `admin_logs` VALUES ('90', '1', 'admin', 'admin/login/check', '登陆', '{\"\\/admin\\/login\\/check\":\"\",\"user_name\":\"admin\",\"user_password\":\"123456\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063971', 'POST');
INSERT INTO `admin_logs` VALUES ('91', '1', 'admin', 'admin', null, '{\"\\/admin\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063971', 'GET');
INSERT INTO `admin_logs` VALUES ('92', '1', 'admin', 'admin/auth_group/lists', '后台权限组', '{\"\\/admin\\/auth_group\\/lists\":\"\"}', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36', '127.0.0.1', '1574063971', 'GET');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `sort` tinyint(5) DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) DEFAULT '0' COMMENT '是否显示,1:显示;0:不显示',
  `type` tinyint(1) DEFAULT '1',
  `condition` char(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('3', '0', '权限管理', '', '4', '1', '1', '');
INSERT INTO `menu` VALUES ('4', '0', '系统设置', '', '10', '1', '1', '');
INSERT INTO `menu` VALUES ('5', '4', '菜单管理', 'admin/menu/lists', '1', '1', '1', '');
INSERT INTO `menu` VALUES ('6', '3', '管理员列表', 'admin/admin_user/lists', '1', '1', '1', '');
INSERT INTO `menu` VALUES ('7', '3', '后台权限组', 'admin/auth_group/lists', '0', '1', '1', '');
INSERT INTO `menu` VALUES ('8', '3', '操作日志', 'admin/admin_logs/lists', '2', '1', '1', '');
