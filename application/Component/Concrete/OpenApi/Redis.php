<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/17 0017
 * Time: 13:42
 */

namespace Application\Component\Concrete\OpenApi;
class Redis
{
	public $redis;
	public $key;
	public function __construct ( $redis )
	{
		$this->redis = $redis;
		return $this;
	}
	/**
	 * 加入队列
	 * @param $key
	 * @param $data
	 */
	public function join ( $key, $data )
	{
		$this->key = 'job_queue_'.$key;
		$this->redis->lpush($this->key,$data);
		return $this;
	}

	/**
	 * 获取消息队列数据长度
	 * @return mixed
	 */
	public function getlength(){
       $max = $this->redis->llen($this->key);
       return $max;
	}

	/**
	 * 消费
	 */
	public function working(){
        return $this->redis->rpop($this->key);
	}
}