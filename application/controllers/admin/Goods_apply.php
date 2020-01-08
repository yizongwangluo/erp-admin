<?php

/**
 * 商品申请列表
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Goods_apply extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/goods_apply_data' );
		$this->load->model ( 'data/goods_sku_apply_data' );
		$this->load->model ( 'data/goods_category_data' );
		$this->load->model ( 'facade/goods_distribution_facade' );
	}

	/**
	 * 商品申请列表
	 */
	public function index(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);
		$limit = 10;

		$data = $this->goods_apply_data->get_list($this->admin['id'],$input,$page,$limit);
		$data['category_list'] = $this->goods_category_data->lists();
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 新增商品
	 */
	public function add(){
		$category_list = $this->goods_category_data->lists();
		$this->load->view ('',['category_list'=>$category_list]);
	}

	/**
	 * 修改
	 */
	public function edit($id){

		$view = '@/edit';
		$info = $this->goods_apply_data->get_info($id);
		$category_list = $this->goods_category_data->lists();

		$this->load->view ($view,['info'=>$info,'category_list'=>$category_list]);
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

			if(!$input['code'] && $input['status']==1){ //编码未填写
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写SPU编码');
			}

			$ret = $this->goods_apply_data->update($id,$input);
			//查询是否有sku未填写编码
			if($this->goods_sku_apply_data->get_no_code($id) && $input['status']==1){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写相关SKU编码');
			}
			//修改sku状态
			$sku_app_ret = $this->goods_sku_apply_data->edit_status($id,['status'=>$input['status'],'is_real'=>0]);

			if($sku_app_ret && $ret && $input['status']==1){//同步到主表中

				$c = $this->goods_distribution_facade->synchronization($id);

				if(!$c){ //失败
					$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_distribution_facade->get_error());
				}
			}

		}else{ //新增
			$ret = $this->goods_apply_data->add($this->admin['id'],$input);
		}

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_apply_data->get_error());
		}
	}


	/**
	 * 保存sku
	 */
	public function save_sku(){

		$sku_info = $this->input->post();

		$id = $sku_info['id'];
		unset($sku_info['id']);

		if($id){ //修改
			$ret = $this->goods_sku_apply_data->update($id,$sku_info);
		}else{ //添加
			$ret = $this->goods_sku_apply_data->store($sku_info);
		}

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_sku_apply_data->get_error());
		}

	}


	/**
	 * 删除
	 */
	public function delete(){

		$id = input('id');
		if($id){
			$ret = $this->goods_apply_data->del($id);

			if($ret){ //成功
				$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_apply_data->get_error());
			}
		}
	}


	/**
	 * 分配
	 */
	public function distribution(){

		$input = $this->input->get();
		$input['status'] = $input['status']?$input['status']:2;
		$page = max(1,$input['page']);
		unset($input['page']);
		$limit = 10;

		$data = $this->goods_apply_data->get_list($this->admin['id'],$input,$page,$limit);
		$data['category_list'] = $this->goods_category_data->lists();
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 修改
	 * @param $id
	 */
	public function edit_distribution($id){

		$info = $this->goods_apply_data->get_info($id);
		$category_list = $this->goods_category_data->lists();

		$this->load->view ('',['info'=>$info,'category_list'=>$category_list]);
	}

	/**
	 * 查看详情
	 * @param $id
	 */
	public function info($id){

		$info = $this->goods_apply_data->get_info($id);
		$category_list = $this->goods_category_data->lists();

		$this->load->view ('',['info'=>$info,'category_list'=>$category_list]);
	}

	/**
	 * 保存
	 */
	public function save_distribution(){

		$input = $this->input->post();

		if(!$input['code']){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写SKU编码');
		}

		$id = $input['id'];

		unset($input['id']);

		$ret =  $this->goods_sku_apply_data->update($id,$input);

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_sku_apply_data->get_error());
		}
	}

	/**
	 * 提交审核
	 */
	public function to_examine($id){

		$ret = $this->goods_apply_data->to_examine($id);

		if($ret){ //成功
			$this->goods_sku_data->to_examine_spuid($id);
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_apply_data->get_error());
		}
	}

	/**
	 * 审核
	 */
	public function examine_list(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$data = $this->goods_apply_data->get_list($this->admin['id'],$input,$page);
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 审核
	 */
	public function examine($id){

		$ret = $this->goods_apply_data->to_examine($id);

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_apply_data->get_error());
		}
	}

}