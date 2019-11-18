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
		$this->load->driver ( 'cache', array ('adapter' => 'file', 'backup' => 'file', 'key_prefix' => 'qianrenshu_') );
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

	/**
	 * 公共上传excel
	 * @param string $filename
	 * @return array
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
	 */
	public function _excel_common($filename = ''){

		$data = [];
		if(!file_exists($filename)) { $this->output->ajax_return ( AJAX_RETURN_FAIL, '不存在该文件'); }//判断文件是否存在

		$fileArr  = explode('.',$filename); //获取文件后缀

		$this->load->library("phpexcel");//ci框架中引入excel类

		$objPHPExcel = new PHPExcel();

		if($fileArr[1]=='xls'){
			$objReader = \PHPExcel_IOFactory::createReader('Excel5');

		}elseif($fileArr[1]=='xlsx'){
			$objReader = \PHPExcel_IOFactory::createReader('Excel2007');

		}elseif($fileArr[1]=='csv'){
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
			for($currentColumn='A';$currentColumn<=$highestColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;                    //读取到的数据，保存到数组$arr中
				$data[$currentRow][$currentColumn]= $sheet->getCell($address)->getValue();

				if ($data[$currentRow][$currentColumn] instanceof PHPExcel_RichText) {
					$data[$currentRow][$currentColumn] = $data[$currentRow][$currentColumn]->__toString();
				}
			}
			$data[$currentRow] = array_filter($data[$currentRow]);//过滤空数组
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

		$this->load->library("phpexcel");//ci框架中引入excel类
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


		$fileName = iconv("utf-8", "gb2312", $fileName);

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
}