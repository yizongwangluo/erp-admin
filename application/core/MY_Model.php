<?php

/**
 * 基类模型扩展
 * User: xiongbaoshan
 * Date: 2015/11/10
 * Time: 14:55
 */
class MY_Model extends CI_Model implements \Application\Component\Contract\ErrorReporter
{
	use \Application\Component\Traits\ErrorReporter;

}