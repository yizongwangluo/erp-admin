<?php
/**
 * 微信响应控制器
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/14 0014
 * Time: 15:27
 */
namespace Application\Component\Common\Wechat;
class WeChatResponse extends WeChatBaseController {
	public function __construct(){
		empty($_GET) && exit('invaild fail');
		parent::__construct ();
		// 从项目实例中得到服务端应用实例。
		$server = $this->app->server;
		$userinfo = $this->app->user;
		$server->setMessageHandler(function ($message) use (&$server,&$userinfo){
			// $message->FromUserName // 用户的 openid
			// $message->MsgType // 消息类型：event, text....
            //file_put_contents('/home/www/www.jiaoyitu.com/application/logs/wechatlog', $message->MsgType);
			switch ($message->MsgType) {
				case 'event':
				case 'text':
				case 'image':
				case 'voice':
				case 'video':
				case 'location':
				case 'link':
					$method = 'on'.ucfirst ($message->MsgType);
					return $this->{$method}($message,$server,$userinfo);
					break;
				default:
					return $this->onUnkown ($message,$server,$userinfo);
					break;
			}
		});
		$server->serve()->send ();
	}
	protected function onText($message,$server,$userinfo){
		// TODO: Implement onEvent() method.
	}
	protected function onEvent ($message,$server,$userinfo)
	{
		// TODO: Implement onEvent() method.
	}
	protected function onImage ($message,$server,$userinfo)
	{
		// TODO: Implement onImage() method.
	}
	protected function onLink ($message,$server,$userinfo)
	{
		// TODO: Implement onLink() method.
	}
	protected function onLocation ($message,$server,$userinfo)
	{
		// TODO: Implement onLocation() method.
	}
	protected function onVideo ($message,$server,$userinfo)
	{
		// TODO: Implement onVideo() method.
	}
	protected function onVoice ($message,$server,$userinfo)
	{
		// TODO: Implement onVoice() method.
	}
	protected function onUnkown ($message,$server,$userinfo)
	{
	}
}