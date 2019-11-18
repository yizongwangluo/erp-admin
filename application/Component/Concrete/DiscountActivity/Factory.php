<?php
/**
 * 打折活动规则工厂
 * User: xiongbaoshan
 * Date: 2016/8/8
 * Time: 18:09
 */

namespace Application\Component\Concrete\DiscountActivity;


class Factory implements \Application\Component\Contract\Factory
{
    protected function __construct()
    {
    }

    /**
     * 获取实例
     * @param string $type
     * @return \Application\Component\Contract\DiscountActivity\Rule
     */
    public static function get_instance($type)
    {
        static $obj_cache=[];
        if(empty($obj_cache[$type])){
            $class_name="Application\\Component\\Concrete\\DiscountActivity\\Rule\\".ucfirst($type);
            if(!class_exists($class_name)){
                return false;
            }
            $obj_cache[$type]=new $class_name;
        }

        return $obj_cache[$type];

    }
}