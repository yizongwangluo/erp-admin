<?php

/**
 * 个人业绩及收入
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Income extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/income_data' );
	}

	/**
	 * 个人业绩及收入 列表 sc
	 */
	public function index(){

		$where = $this->input->get();
		$where['datetime'] = $where['datetime']? $where['datetime']: date('Y-m');

		$page = max(1,$where['page']);
		unset($where['page']);
		$limit = 10;

		$data = $this->income_data->get_lists($this->admin['id'],$where,$page,$limit);

		$data['where'] = $where;

		$data['page_html'] = create_page_html ( '?', $data['total'], $limit);
		$this->load->view ( '', $data );

	}

}