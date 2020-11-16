<?php
/**
 * 后台权限认证控制器
 * User: xiongbaoshan
 * Date: 2016/7/21
 * Time: 16:04
 */
namespace Application\Component\Common;

//use  Application\Component\Concrete\TongTuApi\ErpApiFactory;

class AdminPermissionValidateController extends AdminSessionValidateController
{
	public function __construct ()
	{
		parent::__construct ();
	}


}