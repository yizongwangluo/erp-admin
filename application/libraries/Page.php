<?php

/**
 * 分页组件
 * User: user
 * Date: 2015/11/18
 * Time: 18:12
 */
class Page
{
	protected $result = array (
		'page_count' => 0,
		'page_start' => 0,
		'page_size' => 0,
	);

	public function init ( $total = 0, $page_num = 1, $page_size = 10 )
	{
		$this->result['page_count'] = ceil ( $total / $page_size );
		$this->result['page_start'] = $page_size * ($page_num - 1);
		$this->result['page_size'] = $page_size;
	}

	public function get_page_count ()
	{
		return $this->result['page_count'];
	}

	public function get_page_start ()
	{
		return $this->result['page_start'];
	}

	public function get_page_size ()
	{
		return $this->result['page_size'];
	}


}