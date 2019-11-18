<?php

/**
 * SQL防注入
 * @author：storm
 * Date: 2017/4/27 0027
 * Time: 17:39
 */
final class Safe
{
	private $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

	/**
	 * 构造函数
	 */
	public function __construct ()
	{
		foreach ( $_GET as $key => $value ) {
			$this->stopattack ( $key, $value, $this->getfilter );
		}
		foreach ( $_POST as $key => $value ) {
			$this->stopattack ( $key, $value, $this->postfilter );
		}
		foreach ( $_COOKIE as $key => $value ) {
			$this->stopattack ( $key, $value, $this->cookiefilter );
		}
	}

	/**
	 * 参数检查并写日志
	 */
	public function stopattack ( $StrFiltKey, $StrFiltValue, $ArrFiltReq )
	{
		if ( is_array ( $StrFiltValue ) ) $StrFiltValue = implode ( $StrFiltValue );
		if ( preg_match ( "/" . $ArrFiltReq . "/is", $StrFiltValue ) == 1 ) {
			$this->writeslog ( $_SERVER["REMOTE_ADDR"] . "    " . strftime ( "%Y-%m-%d %H:%M:%S" ) . "    " . $_SERVER["PHP_SELF"] . "    " . $_SERVER["REQUEST_METHOD"] . "    " . $StrFiltKey . "    " . $StrFiltValue );
			show_404  ( '您提交的参数非法,系统已记录您的本次操作！' );
		}
	}

	/**
	 * SQL注入日志
	 */
	public function writeslog ( $log )
	{
		log_message ( 'Safe_LOG_DEbug', $log, true );
	}

}