<?php
/**
 * 微信初始化控制器
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/14 0014
 * Time: 11:14
 */

namespace Application\Component\Common\Wechat;
use EasyWeChat\Foundation\Application;
class WeChatBaseController extends \MY_Controller
{
	public $app;
	public function __construct ()
	{
		parent::__construct ();
		$this->load->config ( 'wechat' );
		$config_info = $this->config->item ( 'wechat' );
		$this->app =  new Application( $config_info );
	}
}