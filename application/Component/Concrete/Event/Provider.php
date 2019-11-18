<?php
/**
 * 事件提供者
 * User: xiongbaoshan
 * Date: 2016/4/25
 * Time: 16:26
 */

namespace Application\Component\Concrete\Event;


use Application\Component\Concrete\Event\Exception\MissingPayload;
use Application\Component\Concrete\Event\Exception\Undefined;

class Provider
{
    /**
     * 定义系统事件
     * @var array
     */
    protected static $provide_events=array(
        //格式：'事件名称'=>array(事件发生时装载的数据)
        //页面渲染时
        'view_render'=>array('request_uri','route_info','view_path','client_ip'),
        //发货成功时
        'delivery_success'=>['order_id'],





    );


    /**
     * 是否提供事件
     * @param $event
     * @return bool
     */
    public static function has_event($event){
        return isset(self::$provide_events[$event]);
    }

    /**
     * 检测事件
     * @param Payload $payload
     * @throws MissingPayload
     * @throws Undefined
     */
    public static function check_event(Payload $payload){

        if(!self::has_event($payload->event)){
            throw new Undefined("{$payload->event}");
        }

        foreach(self::$provide_events[$payload->event] as $require_field){
            if(!$payload->has($require_field)){
                throw new MissingPayload("require [{$require_field}] on {$payload->event}");
            }
        }
    }

}