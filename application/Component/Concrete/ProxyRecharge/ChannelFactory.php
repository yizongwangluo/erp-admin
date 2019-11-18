<?php
/**
 * 代充商品渠道商工厂
 * User: xiongbaoshan
 * Date: 2016/8/8
 * Time: 18:09
 */

namespace Application\Component\Concrete\ProxyRecharge;


use Application\Component\Contract\Factory;

class ChannelFactory implements Factory
{
    protected function __construct()
    {
    }

    /**
     * 获取实例
     * @param string $type
     * @return \Application\Component\Contract\ProxyRecharge\Channel
     */
    public static function get_instance($type)
    {
        static $obj_cache=[];
        if(empty($obj_cache[$type])){
            $class_name="Application\\Component\\Concrete\\ProxyRecharge\\Channel\\_".$type;
            if(!class_exists($class_name)){
                return false;
            }
            $obj_cache[$type]=new $class_name;
        }

        return $obj_cache[$type];

    }
}