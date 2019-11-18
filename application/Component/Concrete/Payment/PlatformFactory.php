<?php
/**
 * 支付平台工场
 * User: xiongbaoshan
 * Date: 2016/4/6
 * Time: 18:07
 */

namespace Application\Component\Concrete\Payment;


use Application\Component\Contract\ErrorReporter;
use Application\Component\Contract\Payment\Payment;

class PlatformFactory implements ErrorReporter
{
    use \Application\Component\Traits\ErrorReporter;

    private static $instance_list=array();

    /**
     * @param string $type
     * @return \Application\Component\Contract\Payment\Payment
     */
    public function get_instance($type)
    {
        if(isset(self::$instance_list[$type])){
            return self::$instance_list[$type];
        }

        $class_name="\\Application\\Component\\Concrete\\Payment\\Platform\\".ucfirst($type)."\\Payment";
        if(!class_exists($class_name)){
            $this->set_error('支付接口'.$type.'未定义');
            return false;
        }
        $instance=new $class_name;
        if(!($instance instanceof Payment)){
            $this->set_error('支付接口'.$type.'不符合规范');
            return false;
        }
        self::$instance_list[$type]=$instance;
        return $instance;
    }


}