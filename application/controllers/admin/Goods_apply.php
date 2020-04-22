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

		$data['page_html'] = create_page_html ( '?', $data['total'] );

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

		if(empty($input['name'])){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写产品名称');
		}

		if($input['status']==2 || $input['status']==1){ //提交审核
			if(empty($input['name_en'])){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写产品英文名称');
			}
			if(empty($input['dc_name'])){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写中文报关名');
			}
			if(empty($input['dc_name_en'])){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写英文报关名');
			}
			if(empty($input['img'])){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请上传产品图片');
			}
		}

		if($input['status']==3){//驳回时，填写驳回原因
            if(empty($input['disallowance'])){
                $this->output->ajax_return(AJAX_RETURN_FAIL,'请填写驳回原因！');
            }
        }

		if($id){ //修改
			if(!$input['code'] && $input['status']==1){ //编码未填写
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写SPU编码');
			}

			if( $input['status']==1 && $this->goods_apply_data->removal(['id'=>$id,'code'=>$input['code']])){ //判断spu编码是否重复
				$this->output->ajax_return(AJAX_RETURN_FAIL,'该spu编码已存在，无法重复添加');
			}

			$input['edittime'] = time();

			//查询是否有sku未填写编码
            $is_code = $this->goods_sku_apply_data->get_no_code($id);
			if( $is_code && $input['status']==1){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写相关SKU编码');
			}

			//修改sku状态
			$sku_app_ret = $this->goods_sku_apply_data->edit_status($id,['status'=>$input['status'],'is_real'=>0]);
            $ret = $this->goods_apply_data->update($id,$input);
			if($sku_app_ret && $ret && $input['status']==1){//同步到主表中

				$c = $this->goods_distribution_facade->synchronization($id);

				if(!$c){ //失败
                    $stt = array('status' => 2);
                    $this->goods_apply_data->update($id,$stt);
					$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_distribution_facade->get_error());
				}
			}

		}else{ //新增

			$input['u_id'] = $this->admin['id'];

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

		$alias = $sku_info['alias'];

		if($id){ //修改
            if(!empty($alias)){
                $this->goods_apply_data->edit_alias($alias,$id);
            }
			$ret = $this->goods_sku_apply_data->update($id,$sku_info);
		}else{ //添加
		    if(!empty($alias)){
                $this->goods_apply_data->edit_alias($alias,$id = '');
            }
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

			//查询是否还存在sku
			if($this->goods_sku_apply_data->lists(['spu_id'=>$id])){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请先删除产品下的sku再删除该产品！');
			}

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

		$alias = $input['alias'];
		$sku = $input['code'];

        $alias_name = explode(',',$alias);

		if(in_array($sku,$alias_name)){
            $this->output->ajax_return(AJAX_RETURN_FAIL,'sku编码与sku别名重复');
        }

		//查询是否存在该编码
		if($this->goods_sku_apply_data->removal(['id'=>$id,'code'=>$input['code']])){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'该sku编码已存在！');
		}

        $this->goods_apply_data->isset_code($sku);

        if(!empty($alias)){
            $this->goods_apply_data->edit_alias($alias,$id);
        }

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

	//判断别名
    public function alias(){
        $this->goods_apply_data->isset_alias();
    }

}