<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 17:35
 */
class System extends \Application\Component\Common\AdminPermissionValidateController{
	public function __construct ()
	{
		parent::__construct ();
	}

	public function js(){
		$this->load->view('');
	}
}