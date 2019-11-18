<?php
/**
 * 事件载体
 * User: xiongbaoshan
 * Date: 2016/4/25
 * Time: 17:08
 */

namespace Application\Component\Concrete\Event;


class Payload
{
    protected $data=array();
    public function __construct($event,array $data=array())
    {
        $data['event']=$event;
        $this->data=$data;
    }

    /**
     * 转换为数组
     * @return array
     */
    public function to_array(){
        return $this->data;
    }

    /**
     * 是否有某个数据
     * @param $name
     * @return bool
     */
    public function has($name){
        return isset($this->data[$name]);
    }


    public function __get($name)
    {
        return $this->data[$name];
    }

}