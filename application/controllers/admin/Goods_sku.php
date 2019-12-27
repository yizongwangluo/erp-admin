<?php

/**
 * 商品列表
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Goods_sku extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/goods_sku_data' );

	}

	/**
	 * 商品列表
	 */
	public function index(){
		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$data = $this->goods_sku_data->lists_page($input,[],$page);
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 新增商品
	 */
	public function add(){
		$this->load->view ( );
	}

	/**
	 * 修改
	 */
	public function edit($id){

		$view = '@/add';
		$info = $this->goods_sku_data->get_info($id);

		if(input('sh')){
			$view = '@/examine';
		}

		$this->load->view ($view,['info'=>$info]);
	}

	/**
	 * 保存
	 */
	public function save(){

		$input = $this->input->post();
		$id = $input['id'];
		unset($input['id']);
		$input['u_id'] = $this->admin['id'];

		if($id){ //修改
			$ret = $this->goods_sku_data->edit($id,$input);
		}else{ //新增
			$ret = $this->goods_sku_data->add($input);
		}

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_sku_data->get_error());
		}
	}

	public function delete(){

		$id = input('id');
		if($id){
			$ret = $this->goods_sku_data->del($id);

			if($ret){ //成功
				$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_sku_data->get_error());
			}
		}
	}


	/**
	 * 提交审核
	 */
	public function to_examine($id){

		$ret = $this->goods_sku_data->to_examine($id);

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_sku_data->get_error());
		}
	}

	/**
	 * 审核
	 */
	public function examine_list(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$data = $this->goods_sku_data->get_list($this->admin['id'],$input,$page);
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 审核
	 */
	public function examine($id){

		$ret = $this->goods_sku_data->to_examine($id);

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_sku_data->get_error());
		}
	}


	/**
	 * 查询sku列表
	 * @param int $spu_id
	 */
	public function sku_list($spu_id = 0){

		$list = $this->goods_sku_data->lists(['spu_id'=>$spu_id]);
//		foreach($list as $k=>$value){
//			$list[$k]['img']= base_url($value['img']);
//		}
		echo json_encode(['code'=>0,'msg'=>'ok','data'=>$list]);
	}

}