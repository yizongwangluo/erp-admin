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
		$this->load->model ( 'orders/tongtu_orders' );
		$this->load->model ( 'orders/mabang_orders' );
		$this->load->model ( 'data/income_data' );
		$this->load->model ( 'operate/salary_operate' );
		$this->load->model ( 'operate/getoperate_operate' );
		$this->load->model ( 'operate/getoperate_tmp_operate' );
		$this->load->model ( 'operate/getexchange_rate_operate' );
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
		$this->tongtu_orders->get_order_in_shop();
	}

	/**
	 * 每5分钟执行
	 */
	public function run_every_5_minutes(){

		//同步通途订单
//		$this->tongtu_orders->get_order();

		//同步马帮订单
//		$this->mabang_orders->get_order();

	}


	/**
	 * 每小时都执行
	 */
	public function run_every_hour ()
	{
		$this->getexchange_rate_operate->getList(); //同步汇率

		//同步物流单号
		$this->tongtu_orders->get_trackingNumber();
	}

	/**
	 * 每天1点都执行的计划任务
	 */
	public function run_every_day ()
	{
		//定时生成前日运营数据
		$this->getoperate_operate->get_datas();
	}

	/**
	 * 每周星期一都执行的计划任务
	 */
	public function run_every_week ()
	{
		
	}

	/**
	 * 每月十五号执行的计划任务
	 */
	public function run_every_month ()
	{
		//生成个人业绩及收入
		$this->income_data->timing_lists();

		//生成员工薪资列表
		$this->salary_operate->set_salary_list();
	}

	/**
     * 每半小时都执行一次的计划任务
     */
	public function run_every_half_hour ()
    {
		//定时获取shopify订单
//        $this->shopify_orders->sync_order();

		//生成临时每日运营数据
		$this->getoperate_tmp_operate->get_datas_tmp();
    }

}