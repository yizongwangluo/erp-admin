<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/14 0014
 * Time: 18:45
 */
namespace Application\Component\Common\Wechat;
use Application\Component\Common\ILogic;
use EasyWeChat\Foundation\Application;

class WechatLogic extends ILogic{
	public $app;
	public function __construct ()
	{
		parent::__construct ();
		$this->load->config ( 'wechat' );
		$config_info = $this->config->item ( 'wechat' );
		$this->app =  new Application( $config_info );
	}
}