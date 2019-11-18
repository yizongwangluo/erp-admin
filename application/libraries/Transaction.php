<?php

/**
 * 数据库事务
 * User: xiongbaoshan
 * Date: 2015/11/30
 * Time: 15:32
 */
final class Transaction
{
	protected $load = null;

	public function __construct ()
	{
		$this->load = get_instance ()->load;
	}

	/**
	 * 事务开始
	 */
	public function begin ()
	{
		$this->load->database ( '', TRUE )->trans_begin ();
	}

	/**
	 * 事务回滚
	 */
	public function rollback ()
	{
		$this->load->database ( '', TRUE )->trans_rollback ();
	}

	/**
	 * 事务提交
	 */
	public function commit ()
	{
		$this->load->database ( '', TRUE )->trans_commit ();
	}
}