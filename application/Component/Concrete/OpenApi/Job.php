<?php
/**
 * 第三方开发平台 发送续充订单
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/17 0017
 * Time: 13:54
 */

namespace Application\Component\Concrete\OpenApi;
class Job
{
	//redis的key
	protected $key = 'open_continue';
	//redis 对象
	public $r;
	//重试次数
	protected $reSend = 0;
	//回滚数组
	protected $roll_back_arr = array ();

	public function __construct ( $redis, $data, $key = '' )
	{
		$this->r = $r = new Redis( $redis );
		if ( !empty( $key ) ) {
			$this->key = $key;
		}
		$r->join ( $this->key, $data );
	}

	public function start ( $Send )
	{
		$count = 0;
		$len = $this->r->getlength ();
		while ( $count < $len ) {
			$info = $this->r->working ();
			//回滚数组
			$this->roll_back_arr = $info;
			if ( $info == 'nil' || !isset( $info ) ) {
				break;
			}
			$Send->fire ( $info, function () {
				$this->rollBack ();
			} );
			$count++;
		}
		$len = $this->r->getlength ();
		if ( $len > 0 && $this->reSend < 2 ) {
			$this->reSend = ($this->reSend + 1);
			self::start ( $Send );
		} elseif ( $this->reSend >= 2 ) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * 操作失败后，回滚对象
	 */
	public function rollBack ()
	{
		$this->r->join ( $this->key, $this->roll_back_arr );
	}
}