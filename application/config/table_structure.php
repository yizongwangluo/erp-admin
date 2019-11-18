<?php
/**
 * 建表配置
 * User: liuxiaojie
 * Date: 2018/12/9
 * Time: 15:04
 */
$config = array (

    //数据管理
    'summary_list'=>'CREATE TABLE `{{}}` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `province_id` int(10) NOT NULL DEFAULT \'0\' COMMENT \'省份id\',
                      `year_id` int(10) NOT NULL DEFAULT \'0\' COMMENT \'年份id\',
                      `school_id` int(10) NOT NULL DEFAULT \'0\' COMMENT \'学校id\',
                      `major` varchar(250) NOT NULL COMMENT \'专业\',
                      `major_info` text NOT NULL COMMENT \'专业详情\',
                      `demand_id` int(10) NOT NULL DEFAULT \'0\' COMMENT \'要求id\',
                      `weight` int(10) NOT NULL DEFAULT \'0\' COMMENT \'权重\',
                      `initials` varchar(50) DEFAULT NULL COMMENT \'首字母\',
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT=\'数据管理\';'
);