<?php

/**
 * 提成管理
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Royalty_rules extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/royalty_rules_data' );
		$this->load->model ( 'data/royalty_gpm_data' );
		$this->load->model ( 'data/royalty_px_data' );
		$this->load->model ( 'data/admin_organization_data' );
	}

	/**
	 * 列表
	 */
	public function index(){

		$limit = 10;
		$page = max(1,input('page'));
		$where = $this->input->get();

		$data = $this->royalty_rules_data->get_lists($this->admin['id'],$where,[],$page,$limit);
		$o_list = $this->admin_organization_data->get_field_by_where(['id','name'],[],true);
		$data['o_list'] = array_column($o_list,'name','id');
		$data['page_html'] = create_page_html ( '?', $data['total'], $limit);

		$this->load->view ( '', $data );
	}

	/**
	 * 添加
	 */
	public function add(){

		$data['olist'] = array2level($this->admin_organization_data->lists ());

		$this->load->view ( '', $data );
	}

	/**
	 * 修改
	 */
	public function edit($id){

		$data['info'] = $this->royalty_rules_data->get_info($id); //规则详情

		$data['olist'] = array2level($this->admin_organization_data->lists ()); //组织列表

		$this->load->view ( '', $data );
	}

	/**
	 * px列表
	 */
	public function get_list_px(){

		$r_id = input('r_id');

		$list = $this->royalty_px_data->lists(['r_id'=>$r_id]);

		echo json_encode(['code'=>0,'msg'=>'ok','data'=>$list]);

	}

	/**
	 * gpm列表
	 */
	public function get_list_gpm(){

		$r_id = input('r_id');

		$list = $this->royalty_gpm_data->lists(['r_id'=>$r_id]);

		echo json_encode(['code'=>0,'msg'=>'ok','data'=>$list]);

	}

	/**
	 * 删除px
	 */
	public function del_px(){
		$id = input('id');
		if($id){
			$ret = $this->royalty_px_data->delete($id);
			if(!$ret){
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->royalty_px_data->get_error());
			}else{
				$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
			}
		}
	}
	/**
	 * 删除px
	 */
	public function del_gpm(){
		$id = input('id');
		if($id){
			$ret = $this->royalty_gpm_data->delete($id);
			if(!$ret){
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->royalty_gpm_data->get_error());
			}else{
				$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
			}
		}
	}

	/**
	 * 保存
	 */
	public function save(){

		$input = $this->input->post();

		$id = $input['id'];
		unset($input['id']);
		if($id){ //修改
			$ret = $this->royalty_rules_data->edit($id,$input);

			if(!$ret){
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->royalty_rules_data->get_error());
			}else{
				$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
			}

		}else{ //添加
			$id = $this->royalty_rules_data->add($input);
			if(!$id){ //添加提成规则失败
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->royalty_rules_data->get_error());
			}

			$a = [];$b = [];

			//添加 提成系数px
			if($input['data_px']!='[]'){
				if(!$this->royalty_px_data->add($id,$input['data_px'])){
					$a = ['error'=>'royalty_px_data'];
				}
			}

			//添加 提成系数gpm
			if($input['data_gpm']!='[]'){
				if(!$this->royalty_gpm_data->add($id,$input['data_gpm'])){
					$b = ['error'=>'royalty_px_data'];
				}
			}

			if(!empty($a) || !empty($b)){
				$c = $a?$a:$b;
				$this->output->ajax_return(2,$this->$c['error']->get_error(),['id'=>$id]);
			}else{
				$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
			}
		}
	}

	/**
	 * 保存px
	 */
	public function save_px(){

		$input = $this->input->post();

		if($input['id']){ //修改
			$ret = $this->royalty_px_data->edit($input);
		}else{ //添加
			$ret = $this->royalty_px_data->add_one($input);
		}

		if($ret){
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		}else{
			$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->royalty_px_data->get_error() );
		}
	}

	/**
	 * 保存gpm
	 */
	public function save_gpm(){

		$input = $this->input->post();

		if($input['id']){ //修改
			$ret = $this->royalty_gpm_data->edit($input);
		}else{ //添加
			$ret = $this->royalty_gpm_data->add_one($input);
		}

		if($ret){
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		}else{
			$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->royalty_gpm_data->get_error() );
		}
	}

}