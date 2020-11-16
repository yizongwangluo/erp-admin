<?php

/**
 * 基础控制器扩展
 * User: xiongbaoshan
 * Date: 2015/11/18
 * Time: 13:35
 */
class MY_Controller extends CI_Controller
{

	public function __construct ()
	{
		parent::__construct ();
		$this->_install ();
		$this->_init_env ();
	}

	/**
	 * 安装项目,做一些初始化工作
	 */
	protected function _install ()
	{
		$init_locker = '../install_locker';
		if ( is_file ( $init_locker ) ) return;


		//创建缓存目录
		$init_dir = array (
			'./html',
			'./upload',
			'../application/cache',
			'../application/logs',
		);
		foreach ( $init_dir as $dir ) {
			!is_dir ( $dir ) && mkdir ( $dir, 0777 );
		}


		file_put_contents ( $init_locker, 1 );
	}

	/**
	 * 初始化一些环境变量
	 */
	protected function _init_env ()
	{
		$this->load->driver ( 'cache', array ('adapter' => 'file', 'backup' => 'file', 'key_prefix' => 'erp_') );
		$this->load->vars ( 'static_cdn', config_item ( 'static_cdn' ) );
	}

	/**
	 * 直接加载视图（无需定义控制器操作）
	 * @param $method
	 * @param $params
	 * @return mixed
	 */
	public function _remap ( $method, $params )
	{
		if ( method_exists ( $this, $method ) ) {
			return call_user_func_array ( array ($this, $method), $params );
		} else {
			$this->load->view ();
		}
	}

	public function excel_sum($str = '') {
		$base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$result = 0;

		for ($i = 0, $j = strlen($str) - 1; $i < strlen($str); $i += 1, $j -= 1) {
			$result += pow(strlen($base), $j) * (strpos($base,$str[$i]) + 1);
		}
	  return $result;
	}

	/**
	 * 公共上传excel
	 * @param string $filename
	 * @return array
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
	 */
	public function _excel_common($filename = '',$column = ''){

		$data = [];
		if(!file_exists($filename)) { $this->output->ajax_return ( AJAX_RETURN_FAIL, '不存在该文件'); }//判断文件是否存在

		$fileArr  = explode('.',$filename); //获取文件后缀
		$file_len =  count($fileArr);

		$this->load->library("PHPExcel");//ci框架中引入excel类

		$objPHPExcel = new PHPExcel();

		if($fileArr[$file_len-1]=='xls'){
			$objReader = \PHPExcel_IOFactory::createReader('Excel5');

		}elseif($fileArr[$file_len-1]=='xlsx'){
			$objReader = \PHPExcel_IOFactory::createReader('Excel2007');

		}elseif($fileArr[$file_len-1]=='csv'){
			return $this->read_csv_lines($filename);
		}else{
			$this->output->ajax_return ( AJAX_RETURN_FAIL, '无法读取该excel文件');
		}

		$objPHPExcel = $objReader->load($filename,$encode='utf-8');
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumn = $sheet->getHighestColumn(); // 取得总列数

		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=2;$currentRow<=$highestRow;$currentRow++){                //从哪列开始，A表示第一列
			for($currentColumn='A';$this->excel_sum($currentColumn)<=$this->excel_sum($highestColumn);$currentColumn++){

				//数据坐标
				$address=$currentColumn.$currentRow;                    //读取到的数据，保存到数组$arr中
				$data[$currentRow][$currentColumn]= $sheet->getCell($address)->getValue();

				if ($data[$currentRow][$currentColumn] instanceof PHPExcel_RichText) {
					$data[$currentRow][$currentColumn] = $data[$currentRow][$currentColumn]->__toString();
				}
			}
//			$data[$currentRow] = array_filter($data[$currentRow]);//过滤空数组
			$data[$currentRow] = $data[$currentRow];//过滤空数组
		}
		return array_filter($data);//过滤空数组
	}


	/**
	 * 解析csv文件
	 * @param string $csv_file
	 * @return array
	 */
	public function read_csv_lines($csv_file = '')
	{
		$data = [];
		$file = fopen($csv_file,'r'); //打开文件流

		$Arr = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

		while ($tempData = fgetcsv($file)) { //每次读取CSV里面的一行内容
			foreach($tempData as $key=>$value){
				$key = $Arr[$key] ?  $Arr[$key] : $this->output->ajax_return ( AJAX_RETURN_FAIL, '无法读取该文件');
				$tempData[$key] = iconv('gb2312','utf-8',$value); //文件格式转码
			}
			$data[] = $tempData;
		}

		array_shift($data);//删除文档中第一行

		fclose($file);//关闭文件流
		return $data;
	}


	/**
	 * 导出excel
	 * @param $headArr
	 * @param $data
	 * @param string $fileName
	 * @param int $width
	 * @return bool
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
	 */
	public function _exportExcel($data,$fileName='Excel', $width = 20){

		if (empty($data) && !is_array($data)) {
			return false;
		}

		$date = date("YmdHis",time());
		$fileName .= "_{$date}.xls";

		$this->load->library("PHPExcel");//ci框架中引入excel类
		$objPHPExcel = new PHPExcel();

		$objActSheet = $objPHPExcel->getActiveSheet();
		$border_end = 'A'; // 边框结束位置初始化
		// 写入内容
		$column = 1;

		foreach($data as $key => $rows){ //获取一行数据
			$tem_span = "A";
			foreach($rows as $keyName=>$value){// 写入一行数据
				if (strlen($tem_span) > 1) {
					$arr_span = str_split($tem_span);
					$j = '';
					foreach ($arr_span as $ke=>$va) {
						$j .= chr(ord($va));
					}
				} else {
					$span = ord($tem_span);
					$j = chr($span);
				}
				$objActSheet->setCellValue($j.$column, $value);
				$border_end = $j.$column;
				$tem_span++;
			}
			$column++;
		}

		$objActSheet->getStyle("A1:".$border_end)->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN); // 设置边框


//		$fileName = iconv("utf-8", "gb2312", $fileName);

		//重命名表
		//$objPHPExcel->getActiveSheet()->setTitle('test');

		//设置活动单指数到第一个表
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=\"$fileName\"");
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output'); //文件通过浏览器下载

	}
	/**
	 * 写excel保存到本地
	 * @param $headArr
	 * @param $data
	 * @param string $fileName
	 * @param int $width
	 * @return bool
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
	 */
	public function _localExcel($data,$fileName='Excel', $width = 20){

		if (empty($data) && !is_array($data)) {
			return false;
		}

		$date = date("YmdHis");
		$fileName .= "_{$date}.xls";

		$this->load->library("PHPExcel");//ci框架中引入excel类
		$objPHPExcel = new PHPExcel();

		$objActSheet = $objPHPExcel->getActiveSheet();
		$border_end = 'A'; // 边框结束位置初始化
		// 写入内容
		$column = 1;

		foreach($data as $key => $rows){ //获取一行数据
			$tem_span = "A";
			foreach($rows as $keyName=>$value){// 写入一行数据
				if (strlen($tem_span) > 1) {
					$arr_span = str_split($tem_span);
					$j = '';
					foreach ($arr_span as $ke=>$va) {
						$j .= chr(ord($va));
					}
				} else {
					$span = ord($tem_span);
					$j = chr($span);
				}
				$objActSheet->setCellValue($j.$column, $value);
				$border_end = $j.$column;
				$tem_span++;
			}
			$column++;
		}

		$objActSheet->getStyle("A1:".$border_end)->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN); // 设置边框

		//设置活动单指数到第一个表
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(FCPATH."upload/errorexcel/".$fileName); //文件通过浏览器下载

		return $fileName;
	}

	//file_get_contents抓取https地址内容
	function getCurl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$result = curl_exec($ch);
		curl_close ($ch);
		return $result;
	}

	/**
	 * 导出CSV
	 * @param        $rows
	 * @param string $file_name
	 */
	function exportCsv($rows, $file_name = '数据')
	{
		$filename =  $file_name. date('YmdHi') . '.csv'; //设置文件名
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=\"" . $filename . "\"");
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');

		$fp    = fopen('php://output', 'a');
		$cnt   = 0; // 计数器
		$limit = 5000; // 每隔$limit行，刷新输出buffer

		//加上bomtou utf-8正常显示，但编辑之后会有问题，要另存为xls、xlsb、xlsx等格式
		fwrite($fp, "\xEF\xBB\xBF");

		foreach ($rows as $row) {
			$cnt++;
			if ($limit == $cnt) { //刷新输出buffer，防止由于数据过多造成问题
				ob_flush();
				flush();
				$cnt = 0;
			}
			fputcsv($fp, $row);
			unset($v);
			unset($row);
		}
	}

	/**
	 * 匹配字符串与数组是否存在相同数据
	 * @param string $str
	 * @param array $arr
	 * @return bool
	 */
	function channel_strs($str = '',$arr = []){

		$c = false;

		if($str && $arr){
			array_map(function($item) use (&$str,&$c){
				if(strstr($str,$item)){
					$c = true;
					return ;
				}
			},$arr);
		}
		return $c;
	}


	/**
	 * 正则匹配并删除字符串
	 * @param string $str
	 * @param array $arr
	 * @param string $a
	 * @return mixed
	 */
	function channel_replace_str ($str = '',$arr = [],$a = ''){

		if($str && $arr){
			$regex = '/('.implode('|',$arr).$a.')/';
			$s = preg_replace($regex,"",$str);
			return $s;
		}
		return false;
	}
}