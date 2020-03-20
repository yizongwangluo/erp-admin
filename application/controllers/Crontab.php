<?php

/**
 * 计划任务
 * User:storm
 * Date: 2017/4/19
 * Time: 9:13
 */
class Crontab extends \MY_Controller
{
	public function __construct ()
	{
		parent::__construct ();
		set_time_limit ( 0 );
		$this->load->model ( 'orders/shopify_orders' );
		$this->load->model ( 'data/income_data' );
		$this->load->model ( 'operate/salary_operate' );
		$this->load->model ( 'operate/getoperate_operate' );
	}

	/**
	 * 测试的
	 */
	public function test ()
	{
		echo 'allow access';
	}

	/**
	 * 每分钟都执行的计划任务
	 */
	public function run_every_minute ()
	{
		log_message('run_every_minute',date('Y-m-d H:i:s'),true);
	}

	/**
	 * 每小时都执行
	 */
	public function run_every_hour ()
	{
	}

	/**
	 * 每天1点都执行的计划任务
	 */
	public function run_every_day ()
	{
//		$this->shopify_orders->index(); //定时获取shopify订单

		log_message('run_every_day','每天1点都执行的计划任务'.date('Y-m-s'),true);
		$this->getoperate_operate->get_datas(); //定时生成前日运营数据

	}

	/**
	 * 每周星期一都执行的计划任务
	 */
	public function run_every_week ()
	{
	}

	/**
	 * 每月2号都执行的计划任务
	 */
	public function run_every_month ()
	{
		$this->income_data->timing_lists();
		$this->salary_operate->set_salary_list();
	}

	/**
     * 每半小时都执行一次的计划任务
     */
	public function run_every_half_hour ()
    {
        $this->shopify_orders->sync_order();
    }
}