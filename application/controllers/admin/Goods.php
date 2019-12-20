<?php

/**
 * 商品列表
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Goods extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/goods_data' );
		$this->load->model ( 'data/goods_sku_data' );

	}

	/**
	 * 商品列表
	 */
	public function index(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$data = $this->goods_data->get_list($this->admin['id'],$input,$page);
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
		$info = $this->goods_data->get_info($id);

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
			$ret = $this->goods_data->edit($id,$input);

			if($input['status']!==false){
				$ret = $this->goods_sku_data->edit_spuid($id,$input['status']); //修改对应sku状态
				if($ret && $input['status']==1){ //审核通过，同步sku到通途系统
					$this->add_sku_tongtu($id,$input);
				}
			}

		}else{ //新增
			$ret = $this->goods_data->add($input);
		}

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_data->get_error());
		}
	}

	/**
	 * 同步商品到通途
	 * @param int $id
	 * @param array $input
	 */
	public function add_sku_tongtu($id = 0,$input = []){

		if($id && $input){
			$data = [];
			$data['productCode'] = $input['code'];
			$data['productName'] = $input['code'];
			$data['productStatus'] = "1";

			$sku_list = $this->goods_sku_data->lists(['spu_id'=>$id,'is_real'=>0]);

			$data['salesType'] = $sku_list?"1":"0"; //有sku时为变参销售，没有则是普通销售

			foreach($sku_list as $k=>$v){
				$data['goods'][$k]['goodsAverageCost'] = $v['price']; //货品平均成本
				$data['goods'][$k]['goodsCurrentCost'] = $v['price']; //货品成本(最新成本)
				$data['goods'][$k]['goodsSku'] = $v['code']; //货号(SKU)
				$data['goods'][$k]['goodsVariation'][0]['variationName'] = $v['norms_type'];//规格名称
				$data['goods'][$k]['goodsVariation'][0]['variationValue'] = $v['norms'];//规格值
				$data['goods'][$k]['goodsWeight'] = (int)$v['weight']; //重量 克
			}

			$ret = $this->erpApi->add_goods($data);

			if(!$ret){
				$this->output->ajax_return(AJAX_RETURN_FAIL,'商品同步到通途失败，请手动同步！');
			}
		}
	}

	public function delete(){

		$id = input('id');
		if($id){
			$ret = $this->goods_data->del($id);

			if($ret){ //成功
				$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_data->get_error());
			}
		}
	}

	/**
	 * 提交审核
	 */
	public function to_examine($id){

		$ret = $this->goods_data->to_examine($id);

		if($ret){ //成功
			$this->goods_sku_data->to_examine_spuid($id);
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_data->get_error());
		}
	}

	/**
	 * 审核
	 */
	public function examine_list(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$data = $this->goods_data->get_list($this->admin['id'],$input,$page);
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 审核
	 */
	public function examine($id){

		$ret = $this->goods_data->to_examine($id);

		if($ret){ //成功
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_data->get_error());
		}
	}

}