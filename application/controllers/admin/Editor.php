<?php

class Editor extends \Application\Component\Common\AdminPermissionValidateController {

	public function index(){
		error_reporting(E_ERROR);
		header("Content-Type: text/html; charset=utf-8");
		$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(FCPATH."./static/admin/ueditor/php/config.json")), true);
		$action = $_GET['action'];
		switch ($action) {
			case 'config':
				$result =  json_encode($CONFIG);
				break;
			/* 上传图片 */
			case 'uploadimage':
				/* 上传涂鸦 */
			case 'uploadscrawl':
				/* 上传视频 */
			case 'uploadvideo':
				/* 上传文件 */
			case 'uploadfile':
				$fieldName = $CONFIG['imageFieldName'];
				$result = $this->upFile($fieldName);
				break;
			default:
				$result = json_encode(array(
						'state' => '该功能已禁用，请求地址出错'
				));
				break;
		}

		/* 输出结果 */
		if(isset($_GET["callback"])){
			if(preg_match("/^[\w_]+$/", $_GET["callback"])){
				echo htmlspecialchars($_GET["callback"]).'('.$result.')';
			}else{
				echo json_encode(array(
						'state' => 'callback参数不合法'
				));
			}
		}else{
			echo $result;
		}
	}

	//上传文件
	public function upFile($fieldName){

		if($fieldName!='file'){
			$file = $_FILES[$fieldName];
		}else{
			$file = $_FILES['file'];
		}

		if(explode('/',$file['type'])[0]=='image'){ //图片

			$file_path = FCPATH .'upload/images/'.date("Ymd").'/';

			$filearr = explode('.', $file["name"]);

			$name =uniqid ( rand_string ( 10 ) ) .'.'.$filearr[count($filearr)-1];
			$url = $this->config->config['img_url']. '/upload/images/'.date("Ymd").'/'.$name;

		}else{ //文件

			$file_path = FCPATH .'upload/files/';
			$fileNameArr = explode('.',$file["name"]);
			$name = uniqid ( rand_string ( 10 ) ).'.'.$fileNameArr[count($fileNameArr)-1];
			$url =FCPATH.'upload/files/'.$name;
		}

		if(!is_dir($file_path)){
			mkdir ($file_path,0777,true);
		}

		if(move_uploaded_file ($file['tmp_name'], $file_path.$name)){
			$ddd=array(
					'state' => 'SUCCESS',
					'url' => $url
			);
		}else{
			$ddd =array (
					'state' => 'errer'
			);
		}
		echo_json ($ddd);
	}
}