<?php

/**
 * sku备货
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Sku_stock extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/purchase_data' );

	}

	/**
	 * sku备货列表
	 */
	public function index(){

		$input = $this->input->get();
		unset($input['page']);
		$page = max(1,input('page'));

		$data = $this->purchase_data->lists_page_uid($this->admin['id'],$input,$page);

		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );

	}

	/**
	 * 审核
	 */
	public function edit($id){

		if($id){

			$info =  $this->purchase_data->get_info($id);

			$this->load->view ( '@/examine', ['info'=>$info]);
		}
	}

	/**
	 * 我的申请
	 */
	public function lists(){

		$status = input('status');
		$page = max(1,input('page'));

		$input['u_id'] = $this->admin['id'];
		if(is_numeric($status)){
			$input['status'] = $status;
		}

		$data = $this->purchase_data->lists_page($input,['id','desc'],$page);

		$data['where'] = ['status'=>$status];

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 备货申请
	 */
	public function save(){

		$input = $this->input->post();
		$id = $input['id'];
		unset($input['id']);


		if($id){ //修改

			$input['approval_u_id'] = $this->admin['id'];
			$input['approval_time'] = time();

			$ret = $this->purchase_data->edit($id,$input);
		}else{ //新增
			$input['u_id'] = $this->admin['id'];

			$ret = $this->purchase_data->add($input);
		}

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->purchase_data->get_error());
		}
	}

	/**
	 * 删除
	 */
	public function delete(){

		$id = input('id');

		if($id){
			$ret = $this->purchase_data->delete($id);

			if($ret){ //成功
				$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->purchase_data->get_error());
			}
		}
	}

}