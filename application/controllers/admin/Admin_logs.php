<?php

/**
 * 管理员日志
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/31 0031
 * Time: 14:17
 */
class Admin_logs extends \Application\Component\Common\AdminPermissionValidateController
{
	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/admin_logs_data' );
	}

	public function lists ()
	{
		$page = max ( 1, $this->input->get ( 'page' ) );
		$condition = $this->parse_query_lists ($this->input->get ());
		$result = $this->admin_logs_data->lists_page ( $condition, ['id', 'desc'], $page );
		$result['page_html'] = create_page_html ( '?', $result['total'] );
		$this->load->view ( '', $result );
	}
	/**
	 * 构造查询条件
	 */
	private function parse_query_lists ($input)
	{
		$start_time = $input['start_time'];
		$end_time = $input['end_time'];
		if($start_time != '' && $end_time != ''){
			//结束时间与开始时间的时间差
			$time_stamp_diff = strtotime($end_time) - strtotime($start_time);
			if($time_stamp_diff <= 0)
				$this->output->alert('对不起,查询开始时间必须小于结束时间!');
		}
		$condition = array ();
		$username = $input['username'];
		if (!empty($username)){
			$condition[] = " username like '%{$username}%' ";
		}
		if (!empty($start_time)){
			$condition[] = " dateline >= ".strtotime($start_time);
		}
		if (!empty($end_time)){
			$condition[] = " dateline <= ".strtotime($end_time);
		}
		if (empty($condition)){
			return array ();
		}else{
			return implode (' and ',$condition);
		}

	}
}