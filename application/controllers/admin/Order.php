<?php

/**
 * 订单列表
 */

use  Application\Component\Concrete\TongTuApi\ErpApiFactory;

class Order extends \Application\Component\Common\AdminPermissionValidateController
{

	protected $shoplist;
	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/order_data' );
		$this->load->model ( 'data/order_goods_data' );
		$this->load->model ( 'data/excel_error_log_data' );

		$this->shoplist = model('data/shop_data')->get_field_by_where(['id','domain'],[],true); //店铺列表

	}

	/**
	 * 列表
	 */
	public function index(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$input = array_filterempty($input); //清空空数组

		if(isset($input['error_order'])){
			if($input['error_order']==1){
				$input['user_id <>'] = 0;
			}else{
				$input['user_id'] = 0;
			}
			unset($input['error_order']);
		}

		$data = $this->order_data->lists_page($input,['id','desc'],$page);
		$data['where'] = $input;

		$data['page_html'] = create_page_html ( '?', $data['total'] );

		$data['shoplist'] = array_column($this->shoplist,null,'id');

		$this->load->view ( '', $data );
	}

	/**
	 * 查看详情
	 * @param $id
	 */
	public function info($id){

		$info = $this->order_data->get_info($id);

		$this->load->view ('',['info'=>$info]);
	}

	/**
	 * 订单商品列表
	 * @param $id
	 * @return string
	 */
	public function orderGoodslist($id){

		$list = $this->order_goods_data->lists(['o_id'=>$id]);

		$ret =['code'=>0,'msg'=>'ok','data'=>$list];

		echo json_encode($ret);

	}


	/**
	 * 导入运费
	 */
	public function import_save(){

		$file_name = input('file_name');
		if(!$file_name){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'请上传文件');
		}

		$data = $this->_excel_common($file_name,'C');//读取excel文件
		$error = [];

		$date_list = [];

		foreach($data as $k=>$v){

			$input = [];
			$input['tracking_number'] = $v['A'];
			$input['freight'] = $v['B'];

			$ret = $this->order_data->add_import($input);

			if($ret==false){
				$v['C'] = $this->order_data->get_error();
				$error[] = $v;
			}else{
				$date_list[] = $ret;//获取店铺ID 和时间
			}
		}

		$date_list = array_unique($date_list);//去重
		//循环刷新运营数据
		foreach($date_list as $v){
			$info = explode('|',$v);
			model ( 'operate/getoperate_operate' )->get_data($info[1],$info[0]);
		}

		if(count($error)){
			//保存到数据库
			$this->excel_error_log_data->store(['name'=>'导入失败','datetime'=>date('Y-m-d H:i:s'),'content'=>json_encode($error),'u_id'=>$this->admin['id'],'type'=>2]);
			$this->output->ajax_return(AJAX_RETURN_FAIL,'导入失败，请下载失败日志');
		}

		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'OK');
	}


	/**
	 * 查询错误日志列表
	 */
	public function error_log(){

		$list = $this->excel_error_log_data->get_field_by_where(['id','name','datetime','u_id'],['type'=>2],true);

		$this->load->view ( '', ['list'=>$list] );
	}

}