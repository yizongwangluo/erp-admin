<?php
/**
 * 事件监听者
 * User: xiongbaoshan
 * Date: 2016/4/25
 * Time: 16:17
 */

namespace Application\Component\Concrete\Event;


class Observer
{
    protected $dispatcher=null;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher=$dispatcher;
    }

    /**
     * 注册事件监听者
     * @throws Exception\Undefined
     */
    public function register(){

        //页面调试信息
        $this->dispatcher->on('view_render',function(Payload $payload){
            //debug_dump($payload->to_array());
        });

        $this->dispatcher->on('delivery_success',function(Payload $payload){
	        /*
	        $ci=get_instance();
			$ci->load->model('facade/hd_lhb2016_facade');
			 if($ci->hd_lhb2016_facade->get_activity_status()!==0){
				 log_message('lhb2016_debug','activity status invalid',true);
				 return;
			 }
			 $call_method="on_{$payload->event}";
			 $ci->hd_lhb2016_facade->{$call_method}($payload);*/
        });


    }

}