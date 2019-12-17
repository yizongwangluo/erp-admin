<?php
/**
 * 助手函数
 * User: xiongbaoshan
 * Date: 2016/4/28
 * Time: 17:29
 */


/**调试输出函数(建议所有小伙伴使用)
 * @param string $var
 */
function debug_dump ( $var = '' )
{

	$is_ajax_request = isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : '';
	$is_product_env = ENVIRONMENT == 'production';

	if ( $is_product_env || $is_ajax_request ) {
		return;
	}

	echo "<pre>";
	var_dump ( $var );
	echo "</pre>";
}


/**
 * 数组转excel
 * @param array $array
 * @param string $file_name
 * @param bool|true $download
 */
function array_to_excel ( array $array, $file_name = 'export.csv', $download = true )
{

	$fh = fopen ( "php://temp", 'r+' );
	$fsize = 0;
	$fields = array_keys ( $array[0] );
	$fsize += fputcsv ( $fh, $fields );
	foreach ( $array as $row ) {
		$fsize += fputcsv ( $fh, $row );
	}
	rewind ( $fh );
	$csv = fread ( $fh, $fsize );
	fclose ( $fh );

	if ( $download ) {
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
		header ( 'Content-Disposition: attachment;filename=' . basename ( $file_name ) );
        echo "\xEF\xBB\xBF";
		echo $csv;
	} else {
		file_put_contents ( $file_name, $csv );
	}


}

function isUrl ( $this_url )
{

	$p = '/^http[s]?:\/\/' .
		'(([0-9]{1,3}\.){3}[0-9]{1,3}' .             // IP形式的URL- 199.194.52.184
		'|' .                                        // 允许IP和DOMAIN（域名）
		'([0-9a-z_!~*\'()-]+\.)*' .                  // 三级域验证- www.
		'([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.' .     // 二级域验证
		'[a-z]{2,6})' .                              // 顶级域验证.com or .museum
		'(:[0-9]{1,4})?' .                           // 端口- :80
		'((\/\?)|' .                                 // 如果含有文件对文件部分进行校验
		'(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/';

	if ( !preg_match ( $p, trim($this_url) ) ) {
		return false;
	}

	return true;
}


/**
 * excel转数组
 * @param string $excel_path
 * @param array $field_map
 * @param int $row_offset
 * @param int $sheet_index
 * @return array
 */
function excel_to_array ( $excel_path = '', $field_map = array (), $row_offset = 0, $sheet_index = 0 )
{

	$excel = IOFactory::load ( $excel_path );
	$sheet = $excel->getActiveSheet ( $sheet_index );
	$data = array ();

	$row_iterator = $sheet->getRowIterator ( 1 + $row_offset );
	foreach ( $row_iterator as $k => $row ) {
		if ( $k <= $row_offset - 1 ) continue;
		$cell_iterator = $row->getCellIterator ();
		$row = array ();
		foreach ( $cell_iterator as $i => $cell ) {
			$value = $cell->getFormattedValue ();
			if ( isset( $field_map[$i] ) ) {
				$row[$field_map[$i]] = $value;
			}
		}
		$data[] = $row;
	}

	return $data;
}

/**
 * 创建
 * @param $base_url 基本URL
 * @param $total 总数量
 * @param $page_size 每页数量
 * @return mixed
 */
function create_page_html ( $base_url, $total, $page_size = 17 )
{
	$ci = get_instance ();
	$ci->load->library ( 'pagination' );
	$config['page_query_string'] = true;
	$config['reuse_query_string'] = true;
	$config['use_page_numbers'] = TRUE;
	$config['num_links'] = 5;
	$config['query_string_segment'] = 'page';
	$config['prev_link'] = '上一页';
	$config['next_link'] = '下一页';
	$config['first_link'] = '首页';
	$config['last_link'] = '尾页';
	$config['full_tag_open'] = '<div class="layui-box layui-laypage layui-laypage-molv">';
	$config['full_tag_close'] = '</div>';
	$config['base_url'] = $base_url;
	$config['total_rows'] = $total;
	$config['per_page'] = $page_size;
	$config['cur_tag_open'] = '<a class="layui-laypage-curr on">';
	$config['cur_tag_close'] = '</a>';
	$ci->pagination->initialize ( $config );
	return $ci->pagination->create_links ();
}


/**
 * 自动为URL追加参数
 * @param array $args
 * @param null $url
 * @return string
 */
function add_args_to_url ( $args = [], $url = null )
{
	if ( !$url ) {
		$url = (!empty( $_SERVER['HTTPS'] ) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	$parse_result = parse_url ( $url );

	$get_args = [];
	if ( isset( $parse_result['query'] ) ) {
		parse_str ( $parse_result['query'], $get_args );
	} elseif ( $_GET ) {
		$get_args = $_GET;
	}
	$args = array_merge ( $get_args, $args );

	$path_info = explode ( '/', $parse_result['path'] );
	foreach ( $args as $k => $v ) {
		if ( is_numeric ( $k ) && isset( $path_info[$k] ) ) {
			if ( $v === null ) {
				unset( $path_info[$k] );
			} else {
				$path_info[$k] = $v;
			}
			unset( $args[$k] );
			continue;
		}

		if ( $v === null && isset( $args[$k] ) ) {
			unset( $args[$k] );
			continue;
		}
	}
	$parse_result['path'] = implode ( '/', $path_info );


	$parse_result['query'] = http_build_query ( $args );
	if ( $parse_result['query'] ) {
		$parse_result['query'] = '?' . $parse_result['query'];
	}
	if ( isset( $parse_result['port'] ) ) {
		$parse_result['port'] = ':' . $parse_result['port'];
	}
	$parse_result['scheme'] = $parse_result['scheme'] . '://';
	return implode ( '', $parse_result );
}

/**
 * 计算N天下线
 * @param $offline_time
 * @param null $now
 * @return int
 */
function offline_day ( $offline_time, $now = null )
{
	if ( !$now ) {
		$now = time ();
	}

	if ( $offline_time <= $now ) {
		return 0;
	} else {
		return intval ( ($offline_time - $now) / 86400 ) + 1;
	}

}

/**
 * 隐私信息替换显示
 * @param $string
 * @param string $replace_pos
 * @param string $replace_chars
 * @return bool|string
 */
function half_replace ( $string, $replace_pos = 'center', $replace_chars = '***' )
{

	if ( $string == '-' || empty( $string ) ) {
		return $string;
	}

	$length = mb_strlen ( $string );
	$replace_len = mb_strlen ( $replace_chars );

	$max_replace_rate = 0.6;
	if ( ($replace_len / $length) > $max_replace_rate ) {
		$replace_len = $length * $max_replace_rate;
	}

	if ( $replace_pos == 'center' ) {
		$start_pos = intval ( $length / 2 ) - intval ( $replace_len / 2 );
		return mb_substr ( $string, 0, $start_pos ) . $replace_chars . mb_substr ( $string, $start_pos + $replace_len );
	}

	if ( $replace_pos == 'left' ) {
		return $replace_chars . mb_substr ( $string, $replace_len );
	}

	if ( $replace_pos == 'right' ) {
		return mb_substr ( $string, 0, -$replace_len ) . $replace_chars;
	}

	return false;
}


/**
 * 发送GET请求
 * @param $url
 * @param int $timeout
 * @return mixed
 */
function curl_get ( $url, $timeout = 25 )
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	$file_contents = curl_exec ( $ch );
	curl_close ( $ch );
	return $file_contents;
}

/**
 * 发送POST请求
 * @param $url
 * @param $data
 * @return mixed
 */
function curl_post ( $url, $data )
{
	$ch = curl_init ();
	$timeout = 15;
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	// post数据
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	// post的变量
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$file_contents = curl_exec ( $ch );
	curl_close ( $ch );
	return $file_contents;
}

/**
 * 随机生成字符串
 * @param int $length
 * @return string
 */
function rand_string ( $length = 8 )
{
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$randString = '';
	$len = strlen ( $str ) - 1;
	for ( $i = 0; $i < $length; $i++ ) {
		$num = mt_rand ( 0, $len );
		$randString .= $str[$num];
	}
	return $randString;
}

/**
 * 随机生成数字
 * @param int $len
 * @return string
 */

function rand_number ( $len = 6 )
{
	$code = '';
	for ( $i = 0; $i < $len; $i++ ) {
		$code .= rand ( 1, 9 );
	}
	return $code;
}


/**
 * 判断是否手机
 * @param $phone
 * @return bool
 */
function is_mobile ( $phone )
{
	if (
		empty( $phone )
		|| mb_strlen ( $phone ) !== 11
		|| !is_numeric ( $phone )
	) {
		return false;
	}

	return true;
}


/**
 * 发送短信（todo:发送短信前必需在运营商备案短信模板,否则无法发送成功）
 * @param $phone 发送目标
 * @param $text 短信内容
 * @param string $type 短信类型
 * @return int
 */
function send_sms ( $phone, $text, $type = 'verify_code' )
{
	$username = 'cly256g';
	$password = '5z6q9tr4';
	$pwd = md5 ( $username . md5 ( $password ) );
	//短信平台状态码
	$code_map = array (
		'0' => '失败',
		'-1' => '用户名或者密码不正确',
		'-2' => '必填选项为空',
		'-3' => '短信内容0个字节',
		'-4' => '0个有效号码',
		'-5' => '余额不够',
		'-10' => '用户被禁用',
		'-11' => '短信内容超过500字',
		'-12' => '无扩展权限（ext字段需填空）',
		'-13' => 'IP校验错误',
		'-14' => '内容解析异常',
		'-990' => '未知错误',
	);

	if ( !is_mobile ( $phone ) ) {
		return 0;
	}
	$text = $text . '【交易兔-游戏交易平台】';
	$url = 'http://sms-cly.cn/smsSend.do?username=' . $username . '&password=' . $pwd . '&mobile=' . $phone . '&content=' . $text;
	$code = curl_get ( $url );
	$ci = get_instance ();
	$ci->load->model ( 'data/sms_send_log_data' );
	$ci->sms_send_log_data->store ( [
		'type' => $type,
		'mobile' => $phone,
		'content' => $text,
		'result' => $code,
		'ip' => $ci->input->ip_address (),
		'dateline' => time (),
	] );
	if ( $code <= 0 ) {
		log_message ( 'sms_error', 'sms send content[' . $text . '] to [' . $phone . '] error[' . $code_map[$code] . ']', true );
		return 0;
	}

	return 1;
}


/**
 * 发送短信-腾讯云（todo:发送短信前必需在运营商备案短信模板,否则无法发送成功）
 * @param $phone 发送目标
 * @param $params 参数
 * @param $templId 模板id
 * @param $type 短信类型
 * @return int
 */
function qcloud_sns($phone, $params, $templId, $type = 'verify_code' ){

	$appid  = '1400054336';
	$appkey = 'fe79be8866ec915d7ae7395602f5fb93';
	try {

		if ( !is_mobile ( $phone ) ) {
			return 0;
		}

		$sender = new \Application\Component\Units\Qcloud\Sms\SmsSingleSender($appid, $appkey);
		$mobile = $phone;    //手机号
		$text =  '交易兔';
		$content = '模板id：'.$templId.'-参数：'.json_encode($params);

		// 假设模板内容为：您的验证码是：{1}，为了您的账号安全，请勿将验证码告知他人。(此验证码{2}秒内有效)
		$result = $sender->sendWithParam("86", $mobile, $templId, $params, $text, "", "");
		$rsp = json_decode($result, true);

		$ci = get_instance ();
		$ci->load->model ( 'data/sms_send_log_data' );

		$ci->sms_send_log_data->store ( [
				'type' => $type,
				'mobile' => $mobile,
				'content' => $content,
				'result' => $rsp['result'],
				'ip' => $ci->input->ip_address (),
				'dateline' => time ()
		] );

		if ($rsp['result'] != 0) {
			log_message ( 'sms_error', 'sms send content[' . $text . '] to [' . $phone . '] error[' . json_encode($params) . '] info ['.$result.']', true );
			return false;
		}

		return true;

	} catch(\Exception $e) {

		log_message ( 'sms_error', 'sms send content[' . $text . '] to [' . $phone . '] error[' . json_encode($params) . '] info ['.$result.']', true );

		return false;
	}

}


/**
 * 校验验证码
 * @param $dest
 * @param $code
 * @return bool
 */
function check_verify_code ( $dest, $code )
{
	$code_info = $_SESSION['verify_code'][$dest];
	if ( !$code_info ) {
		return false;
	}
	if ( $code_info['timeout'] < time () ) {
		unset( $_SESSION['verify_code'][$dest] );
		return false;
	}
	if ( $code_info['code'] != $code ) {
		return false;
	} else {
		$GLOBALS['destroy_verify_code'] = $dest;
		return true;
	}


}

/**
 * 销毁验证码
 */
function destroy_verify_code ()
{
	if ( isset( $GLOBALS['destroy_verify_code'] ) ) {
		unset( $_SESSION['verify_code'][$GLOBALS['destroy_verify_code']] );
	}
}

/**
 * 发送邮件
 * @param $email
 * @param $subject
 * @param $body
 * @return bool
 * @throws phpmailerException
 */
function send_mail ( $email, $subject, $body )
{
	get_instance ()->load->helper ( 'email' );
	if ( !valid_email ( $email ) ) {
		return 0;
	}

	require_once ('../application/libraries/email/class.phpmailer.php');
	require_once ("../application/libraries/email/class.smtp.php");
	$mail = new PHPMailer( true );
	$mail->IsSMTP ();
	$mail->Host = "smtp.exmail.qq.com"; // SMTP server
	$mail->CharSet = "UTF-8";
	$mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
	$mail->SMTPAuth = true;                  // enable SMTP authentication
	$mail->Port = 25;                    // set the SMTP port for the GMAIL server
	$mail->Username = "service@jiaoyitu.com"; // SMTP account username
	$mail->Password = "Aa265265";        // SMTP account password
	$mail->AddReplyTo ( 'service@265g.com', '交易兔-游戏交易平台' );
	$mail->AddAddress ( $email, '交易兔用户' );
	$mail->SetFrom ( 'service@jiaoyitu.com', '交易兔-游戏交易平台' );
	$mail->Subject = $subject;
	$mail->AltBody = 'text/html';
	$mail->MsgHTML ( $body );
	return $mail->Send ();
}

/**
 * 是否是身份证
 * @param $string
 * @return bool
 */
//function is_idcard($string){
//    if(empty($string)){
//        return false;
//    }
//    if(!preg_match('/^[\w]{15,18}$/',$string)){
//        return false;
//    }
//    return true;
//}


/**
 * 是否是身份证
 * @param $string
 * @return bool
 */
function is_idcard ( $id_card )
{
	if ( strlen ( $id_card ) == 18 ) {
		return idcard_checksum18 ( $id_card );
	} elseif ( (strlen ( $id_card ) == 15) ) {
		$id_card = idcard_15to18 ( $id_card );
		return idcard_checksum18 ( $id_card );
	} else {
		return false;
	}
}

/**
 * 计算身份证校验码,根据国家标准GB 11643-1999
 * @param $idcard_base
 * @return bool
 */
function idcard_verify_number ( $idcard_base )
{
	if ( strlen ( $idcard_base ) != 17 ) {
		return false;
	}
	//加权因子
	$factor = array (7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
	//校验码对应值
	$verify_number_list = array ('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
	$checksum = 0;
	for ( $i = 0; $i < strlen ( $idcard_base ); $i++ ) {
		$checksum += substr ( $idcard_base, $i, 1 ) * $factor[$i];
	}
	$mod = $checksum % 11;
	$verify_number = $verify_number_list[$mod];
	return $verify_number;
}

/**
 * 将15位身份证升级到18位
 * @param $idcard
 * @return bool|string
 */
function idcard_15to18 ( $idcard )
{
	if ( strlen ( $idcard ) != 15 ) {
		return false;
	} else {
		// 如果身份证顺序码是996 997 998 999,这些是为百岁以上老人的特殊编码
		if ( array_search ( substr ( $idcard, 12, 3 ), array ('996', '997', '998', '999') ) !== false ) {
			$idcard = substr ( $idcard, 0, 6 ) . '18' . substr ( $idcard, 6, 9 );
		} else {
			$idcard = substr ( $idcard, 0, 6 ) . '19' . substr ( $idcard, 6, 9 );
		}
	}
	$idcard = $idcard . idcard_verify_number ( $idcard );
	return $idcard;
}

/**
 * 18位身份证校验码有效性检查
 * @param $idcard
 * @return bool
 */
function idcard_checksum18 ( $idcard )
{
	if ( strlen ( $idcard ) != 18 ) {
		return false;
	}
	$idcard_base = substr ( $idcard, 0, 17 );
	if ( idcard_verify_number ( $idcard_base ) != strtoupper ( substr ( $idcard, 17, 1 ) ) ) {
		return false;
	} else {
		return true;
	}
}


/**
 * 是否是银行卡
 * @param $string
 * @return bool
 */
function is_bank_card ( $string )
{
	if ( empty( $string ) ) {
		return false;
	}
	if ( !is_numeric ( $string ) ) {
		return false;
	}
	if ( strlen ( $string ) < 16 ) {
		return false;
	}
	return true;
}

/**
 * 获取广告位内容
 * @param string $alias
 * @return mixed
 */
function get_ad_content ( $alias = '' )
{
	$CI =& get_instance ();
	$cache_key = 'ad_' . $alias;
	if ( !$data = $CI->cache->get ( $cache_key ) ) {
		$CI->load->model ( 'data/ad_data' );
		$data = $CI->ad_data->get_content ( $alias );
		$CI->cache->save ( $cache_key, $data, 86400 );//永久缓存,后台会自动更新缓存
	}
	return $data;
}

/**
 * 找出数字档位
 * @param $step_config
 * @param $num
 * @return int
 */
function find_step ( $step_config, $num )
{
	$win_step = 0;
	foreach ( $step_config as $step ) {
		if ( $num >= $step ) {
			$win_step = $step;
		}
	}
	return $win_step;
}

/**
 * 如果值为空
 * @param $input_var
 * @param string $default
 * @return string
 */
function if_empty ( $input_var, $default = '' )
{
	return $input_var ? $input_var : $default;
}

/**
 * 线程同步函数,可以保证并发数据安全
 * 用法如下：
 * synchronized(function(){
 *        你要执行的代码
 * });
 */
if ( !function_exists ( 'synchronized' ) ) {
	class synchronized
	{
		private $lock_handle = null;
		private $lock_ready = false;


		public function __construct ( $scope_name = '', $block = true )
		{
			$this->lock_ready = $this->lock_start ( $scope_name, $block );
		}

		public function run ( Closure $callback )
		{
			return call_user_func_array ( $callback, array ($this->lock_ready) );
		}

		public function __destruct ()
		{
			$this->lock_end ();
		}


		private function lock_start ( $lock_name, $block = true )
		{
			$file_path = "/tmp/synchronized_{$lock_name}.tmp";
			touch ( $file_path );
			$this->lock_handle = fopen ( $file_path, 'w' );
			if ( !$this->lock_handle ) {
				return false;
			}
			$lock_mode = $block ? LOCK_EX : (LOCK_EX | LOCK_NB);
			return flock ( $this->lock_handle, $lock_mode );
		}

		private function lock_end ()
		{
			flock ( $this->lock_handle, LOCK_UN );
			fclose ( $this->lock_handle );
			$this->lock_handle = null;
		}


	}

	/**
	 * @param Closure $run
	 * @param bool|true $block
	 * @return mixed
	 */
	function synchronized ( Closure $run, $block = true )
	{
		$call_info = debug_backtrace ( DEBUG_BACKTRACE_IGNORE_ARGS )[1];
		$scope_name = $call_info['class'] . '-' . $call_info['function'];
		$s = new synchronized( $scope_name, $block );
		return $s->run ( $run );
	}
}


/**
 * 获取顶级域名
 * @return string
 */
function get_top_domain ()
{
	$tmp = explode ( ':', $_SERVER['HTTP_HOST'] );//带端口的域名
	$domain = $tmp[0];
	$tmp = explode ( '.', $domain );

	$tmp = array_slice ( $tmp, -2, 2 );
	if ( is_numeric ( $tmp[1] ) ) {//IP形式域名
		$top_domain = $domain;
	} else {//正常域名
		$top_domain = implode ( '.', $tmp );
	}

	return $top_domain;
}

function showForm ( $type = '', $title = '', $options = '', $default = '', $msg = '', $name = '', $must = '' )
{
	$option = '';
	$must = $must ? '<font color="red"> <b>*</b> </font>' : '';
	switch ( $type ) {
		case 'text':
			//文本框
			$option .= '<div class="add_item"><label>' . $title . '：</label><input name="content[' . $name . ']"" value="" placeholder="' . $default . '" type="text" class="txt txtB" />' . $must . '</div>';
			break;
		case 'hidden':
			//隐藏表单
			$option .= '<div class="add_item"><input type="hidden" name="content[' . $name . ']" value="' . $default . '" /></div>';
			break;
		case 'select':
			//下拉菜单
			$option .= '<div class="add_item"><label>' . $title . '：</label><select name="content[' . $name . ']">';
			$arr = explode ( "\n", $options );
			foreach ( $arr AS $k => $val ) {
				$option .= '<option value="' . $val . '">' . $val . '</option>';
			}
			$option .= '</select>' . $must . '</div>';
			break;
	}
	return $option;
}

function checkMust ( $must, $value )
{
	if ( $must ) {
		if ( !$value ) {
			msg ( '信息填写不完整' );
			exit;
		}
	}
}

function msg ( $msg, $back = '', $url = '' )
{
	if ( empty( $msg ) ) $msg = "出错";
	if ( empty( $back ) ) {
		echo "<script type='text/javascript'> alert('$msg');history.go(-1);</script>";
	} elseif ( $back == "subok" ) {
		echo "<script type='text/javascript'> alert('$msg');</script>";
		echo "<script>location.href='$url';</script>";
	}
}

/**
 *  前台页码
 *
 * @access    public
 * @return    integer
 */

if ( !function_exists ( 'multipage' ) ) {
	function multipage ( $num, $perpage, $curpage, $mpurl )
	{
		$pagelen = 7;
		$multipage = '';
		$mpurl .= strpos ( $mpurl, '?' ) !== false ? '&' : '?';
		$realpages = 1;
		if ( $num > $perpage ) {
			$offset = 5;
			$realpages = @ceil ( $num / $perpage );

			if ( $curpage <= $realpages ) {
				$from = $curpage <= $offset ? 1 : ($curpage <= $realpages && $curpage > $realpages - 3 ? $curpage - ($offset - ($realpages - $curpage)) : $curpage - 3);
				$to = $curpage <= $realpages && $curpage >= $realpages - $offset ? $realpages : ($curpage >= $offset ? $curpage + 3 : $pagelen);

				$multipage = '<div class="page">';
				$urlplus = '';

				$multipage .= '<span class="p-num">';

				$multipage .= "<a ";
				if ( $curpage == 1 ) {
					$multipage .= "href=\"{$mpurl}page=1$urlplus\"";
				} else {
					$multipage .= "href=\"{$mpurl}page=" . ($curpage - 1) . "$urlplus\"";
				}
				$multipage .= " class=\"prev-btn\">上一页</a>\n";

				if ( $curpage > $offset && $realpages >= $pagelen ) {
					$multipage .= "<a href=\"{$mpurl}page=1$urlplus\">1</a>";
					$multipage .= "<i>•••</i>\n";
				}

				for ( $i = $from; $i <= $to; $i++ ) {
					if ( $i == $curpage ) {
						$multipage .= '<a href="javascript:;" class="cur">' . $i . '</a>' . "\n";
					} else {
						$multipage .= "<a ";
						$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
						$multipage .= ">$i</a>\n";
					}
				}

				if ( $realpages >= 10 && $curpage < $realpages - $offset ) {
					$multipage .= "<i>•••</i>\n";
					$multipage .= "<a href=\"{$mpurl}page=" . ($realpages - 1) . "$urlplus\">" . ($realpages - 1) . "</a>\n";
					$multipage .= "<a href=\"{$mpurl}page={$realpages}$urlplus\">{$realpages}</a>\n";
				}

				if ( $curpage == $realpages ) {
					$multipage .= "<a ";
					$multipage .= "href=\"{$mpurl}page=" . ($realpages) . "{$urlplus}\"";
				} else {
					$multipage .= "<a ";
					$multipage .= "href=\"{$mpurl}page=" . ($curpage + 1) . "{$urlplus}\"";
				}

				$multipage .= " class=\"next-btn\">下一页</a>\n";

				$multipage .= '</span>' . "\n";

				$multipage .= '<span class="p-skip">' . "\n";
				$multipage .= '<span class="input-box"><input type="text" id="page" name="page" value="" placeholder="跳转到"></span>' . "\n";
				$multipage .= '<a href="javascript:if (document.getElementById(\'page\').value != \'\' && !isNaN(document.getElementById(\'page\').value)) {window.location.href = \'' . preg_replace ( '/(\&|\?)page=(\d)/', '', $_SERVER['REQUEST_URI'] ) . (strpos ( $_SERVER['REQUEST_URI'], '?' ) !== False ? '?' : '?') . 'page=\' + document.getElementById(\'page\').value}">GO</a>' . "\n";
				$multipage .= '</span>' . "\n";

				$multipage .= '</div>' . "\n";
			}
		}
		return $multipage;
	}
}

/**
 * 字符串解密加密
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @return string
 */

function auth_code ( $string, $operation = 'DECODE', $key = 'www.jiaoyitu.com#@!', $expiry = 0 )
{

	$ckey_length = 4;    // 随机密钥长度 取值 0-32;
	// 加入随机密钥,可以令密文无任何规律,即便是原文和密钥完全相同,加密结果也会每次不同,增大破解难度。
	// 取值越大,密文变动规律越大,密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时,则不产生随机密钥

	$key = md5 ( $key ? $key : UC_KEY );
	$keya = md5 ( substr ( $key, 0, 16 ) );
	$keyb = md5 ( substr ( $key, 16, 16 ) );
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), -$ckey_length )) : '';

	$cryptkey = $keya . md5 ( $keya . $keyc );
	$key_length = strlen ( $cryptkey );

	$string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
	$string_length = strlen ( $string );

	$result = '';
	$box = range ( 0, 255 );

	$rndkey = array ();
	for ( $i = 0; $i <= 255; $i++ ) {
		$rndkey[$i] = ord ( $cryptkey[$i % $key_length] );
	}

	for ( $j = $i = 0; $i < 256; $i++ ) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ( $a = $j = $i = 0; $i < $string_length; $i++ ) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr ( ord ( $string[$i] ) ^ ($box[($box[$a] + $box[$j]) % 256]) );
	}

	if ( $operation == 'DECODE' ) {
		if ( (substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 ) ) {
			return substr ( $result, 26 );
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
	}
}

/**
 * 获取最后登录时间
 * @return mixed|string
 */

function getLastLoginTime ()
{
	if ( empty( $_COOKIE['jytkkiz'] ) ) {
		return '';
	}
	$userinfo = $_COOKIE['jytkkiz'];
	$userinfo = auth_code ( $userinfo, 'DECODE', 'www.jiaoyitu.com!@#$' );
	$userinfo = explode ( '!@#', $userinfo );
	return input_Filter ( $userinfo[2] );
}

/**
 * 获取COOKIE用户名
 * @return string
 */

function getUserName ()
{
	if ( empty( $_COOKIE['jytkkiz'] ) ) {
		return '';
	}
	$userinfo = $_COOKIE['jytkkiz'];
	$userinfo = auth_code ( $userinfo, 'DECODE', 'www.jiaoyitu.com!@#$' );
	$userinfo = explode ( '!@#', $userinfo );
	return input_Filter ( $userinfo[1] );
}

/**
 * 获取COOKIE用户ID
 * @return string
 */

function getUserId ()
{
	if ( empty( $_COOKIE['jytkkiz'] ) ) {
		return '';
	}
	$userinfo = $_COOKIE['jytkkiz'];
	$userinfo = auth_code ( $userinfo, 'DECODE', 'www.jiaoyitu.com!@#$' );
	$userinfo = explode ( '!@#', $userinfo );
	return input_Filter ( $userinfo[0] );
}

/**
 * 获取COOKIE用户名
 * @return string
 */

function getNickName ()
{
	if ( empty( $_COOKIE['jytkkiz'] ) ) {
		return '';
	}
	$userinfo = $_COOKIE['jytkkiz'];
	$userinfo = auth_code ( $userinfo, 'DECODE', 'www.jiaoyitu.com!@#$' );
	$userinfo = explode ( '!@#', $userinfo );
	return input_Filter ( $userinfo[3] );
}

/**
 * 参数过滤
 * @param $str
 * @return mixed|string
 */

function input_Filter ( $str )
{
	if ( empty( $str ) ) {
		return '';
	}
	if ( $str == "" ) {
		return $str;
	}
	$str = trim ( $str );
	$str = str_replace ( "&", "&amp;", $str );
	$str = str_replace ( ">", "&gt;", $str );
	$str = str_replace ( "<", "&lt;", $str );
	$str = str_replace ( chr ( 32 ), "&nbsp;", $str );
	$str = str_replace ( chr ( 9 ), "&nbsp;", $str );
	$str = str_replace ( chr ( 34 ), "&", $str );
	$str = str_replace ( chr ( 39 ), "&#39;", $str );
	$str = str_replace ( chr ( 13 ), "<br />", $str );
	$str = str_replace ( "'", "''", $str );
	$str = str_replace ( "select", "sel&#101;ct", $str );
	$str = str_replace ( "join", "jo&#105;n", $str );
	$str = str_replace ( "union", "un&#105;on", $str );
	$str = str_replace ( "where", "wh&#101;re", $str );
	$str = str_replace ( "insert", "ins&#101;rt", $str );
	$str = str_replace ( "delete", "del&#101;te", $str );
	$str = str_replace ( "update", "up&#100;ate", $str );
	$str = str_replace ( "like", "lik&#101;", $str );
	$str = str_replace ( "drop", "dro&#112;", $str );
	$str = str_replace ( "create", "cr&#101;ate", $str );
	$str = str_replace ( "modify", "mod&#105;fy", $str );
	$str = str_replace ( "rename", "ren&#097;me", $str );
	$str = str_replace ( "alter", "alt&#101;r", $str );
	$str = str_replace ( "cast", "ca&#115;", $str );
	return $str;
}

function abslength ( $str )
{
	if ( empty( $str ) ) {
		return 0;
	}
	if ( function_exists ( 'mb_strlen' ) ) {
		return mb_strlen ( $str, 'utf-8' );
	} else {
		preg_match_all ( "/./u", $str, $ar );
		return count ( $ar[0] );
	}
}


/**
 * 发送站内信
 * @param $user_id
 * @param $title
 * @param $content
 */
function site_msg ( $user_id, $title, $content )
{
	model ( 'logic/user_logic' )->send_message ( $user_id, $title, $content );
}

/**
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 * @author : storm
 */
function list_to_tree ( $list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0 )
{
	// 创建Tree
	$tree = array ();
	if ( is_array ( $list ) ) {
		// 创建基于主键的数组引用
		$refer = array ();
		foreach ( $list as $key => $data ) {
			$refer[$data[$pk]] =& $list[$key];
		}
		foreach ( $list as $key => $data ) {
			// 判断是否存在parent
			$parentId = $data[$pid];
			if ( $root == $parentId ) {
				$tree[] =& $list[$key];
			} else {
				if ( isset( $refer[$parentId] ) ) {
					$parent =& $refer[$parentId];
					$parent[$child][] =& $list[$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 数组层级缩进转换
 * @param array $array 源数组
 * @param int $pid
 * @param int $level
 * @return array
 */
function array2level ( $array, $pid = 0, $level = 1 )
{
	static $list = [];
	foreach ( $array as $v ) {
		$v['pid'] = empty($v['pid'])&&$v['children'] ? $v['children']:$v['pid'];
		if ( $v['pid'] == $pid ) {
			$v['level'] = $level;
			$list[] = $v;
			array2level ( $array, $v['id'], $level + 1 );
		}
	}

	return $list;
}

/**
 * 采用一个方法，对数据进行过滤
 * @param $filter  方法
 * @param $data  需要过滤的数据
 * @return array
 */
function array_map_recursive ( $filter, $data )
{
	$result = array ();
	foreach ( $data as $key => $val ) {
		$result[$key] = is_array ( $val )
			? array_map_recursive ( $filter, $val )
			: call_user_func ( $filter, $val );
	}
	return $result;
}

/**
 * 下载文件
 * @param $url 文件URL
 * @param string $downName 文件名称
 */
function download ( $url, $downName = 'jyt' )
{
	ob_start ();
	$type = pathinfo ( $url, PATHINFO_EXTENSION );
	$size = readfile ( $url );
	header ( "Content-type:  application/octet-stream " );
	header ( "Accept-Ranges:  bytes " );
	header ( "Content-Disposition:  attachment;  filename= {$downName}.{$type}" );
	header ( "Accept-Length: " . $size );
}

/**
 * 处理插件钩子
 * @param string $hook 钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook ( $hook, $params = array () )
{
	\Application\Component\Libs\Hook::listen ( $hook, $params );
}

function tree_common($data=array(), $userlist = array(), $pid = 0){
	$tree = '';
	foreach($data as $k => $v)
	{
		if($v['children'] == $pid)
		{
			$v['children'] = tree_common($data,$userlist, $v['id']);
			if($v['flag']==2){
				$v['name'] = $v['name'].'(关)';
			}
			$v['cla'] = 'list';
			foreach($userlist as $v2){
				if($v['id']==$v2['duties_id']){
					$v2['is_disable'] && $v2['user_name']=$v2['user_name'].'(禁用)';
					$v['children'][] = ['id'=>$v2['id'],'name'=>$v2['user_name'],'cla'=>'user'];
				}
			}
			$tree[] = $v;
		}
	}
	return $tree;
}