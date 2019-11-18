<?php
/**
 * 事件调度器
 * User: xiongbaoshan
 * Date: 2016/4/25
 * Time: 15:19
 */

namespace Application\Component\Concrete\Event;


use Application\Component\Concrete\Event\Exception\Undefined;

class Dispatcher
{
    protected $event_listener = array();


    private function __construct()
    {
        $observer = new Observer($this);
        $observer->register();
    }

    /**
     * 获取实例
     * @return static
     */
    public static function get_instance()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new static;
        }

        return $instance;
    }

    /**
     * 触发事件
     * @param Payload $payload
     * @return bool
     * @throws Undefined
     */
    public function fire(Payload $payload)
    {
        Provider::check_event($payload);
        $listener = $this->event_listener[$payload->event];
        if (!isset($listener)) {
            return true;
        }
        foreach ($listener as $callback) {
            if (is_callable($callback)) {
                call_user_func_array($callback,[$payload]);
            }
        }
        return true;

    }

    /**
     * 监听事件
     * @param $events
     * @param \Closure $callback
     * @return bool
     * @throws Undefined
     */
    public function on($events, \Closure $callback)
    {


        if (!is_array($events)) {
            $events = [$events];
        }

        foreach ($events as $event) {
            if (!Provider::has_event($event)) {
                throw new Undefined("{$event}");
            }

            $this->event_listener[$event][] = $callback;
        }
        return true;
    }

}