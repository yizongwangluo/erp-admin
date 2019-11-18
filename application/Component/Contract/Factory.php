<?php
/**
 * 对象工场接口
 * User: xiongbaoshan
 * Date: 2016/4/1
 * Time: 16:41
 */

namespace Application\Component\Contract;


interface Factory
{
    /**
     * 返回一个对象实例
     * @param string $type
     * @return mixed
     */
    public static function get_instance($type);

}