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
		$this->load->model ( 'data/goods_apply_data' );
		$this->load->model ( 'data/goods_sku_data' );
		$this->load->model ( 'facade/goods_distribution_facade' );
		$this->load->model ( 'goods/goods_excel_goods' );
		$this->load->model ( 'data/excel_error_log_data' );
		$this->load->model ( 'data/goods_category_data' );

	}

	/**
	 * 商品列表
	 */
	public function index(){

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

		$data = $this->goods_data->get_list($this->admin['id'],$input,$page);
		$data['category_list'] = $this->goods_category_data->lists();
		$data['where'] = $input;

		$result['page_html'] = create_page_html ( '?', $data['total'] );

		$this->load->view ( '', $data );
	}

	/**
	 * 查看详情
	 * @param $id
	 */
	public function info($id){

		$info = $this->goods_data->get_info($id);
		$category_list = $this->goods_category_data->lists();

		$this->load->view ('',['info'=>$info,'category_list'=>$category_list]);
	}

	/**
	 * 修改
	 */
	public function edit($id){

		$info = $this->goods_data->get_info($id);
		$category_list = $this->goods_category_data->lists();

		$this->load->view ('',['info'=>$info,'category_list'=>$category_list]);
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
	 */
	public function add_sku_tongtu(){

		$id = input('id');

		//查询该spu
		$info = $this->goods_data->get_info($id);
		if(!$info){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'未查询到相关sku信息');
		}

		$data = [];
		$data['productCode'] = $info['code'];
		$data['productName'] = $info['name'];
		$data['productStatus'] = "1";

		$sku_list = $this->goods_sku_data->lists(['spu_id'=>$id]);

		$data['salesType'] = $sku_list?"1":"0"; //有sku时为变参销售，没有则是普通销售

		foreach($sku_list as $k=>$v){
			$data['goods'][$k]['goodsAverageCost'] = $v['price']; //货品平均成本
			$data['goods'][$k]['goodsCurrentCost'] = $v['price']; //货品成本(最新成本)
			$data['goods'][$k]['goodsSku'] = $v['code']; //货号(SKU)
			$data['goods'][$k]['goodsVariation'][0]['variationName'] = 'norms';//规格名称
			$data['goods'][$k]['goodsVariation'][0]['variationValue'] = $v['norms'];//规格值
			$data['goods'][$k]['goodsWeight'] = (int)$v['weight']; //重量 克
		}

		if(!$info['is_tongtu']){
			$ret = $this->erpApi->add_goods($data);
		}else{
			$this->output->ajax_return(AJAX_RETURN_FAIL,'更新接口尚未接入');
		}

		if(!$ret['code']){
			$this->output->ajax_return(AJAX_RETURN_FAIL,$ret['msg']);
		}else{
			//修改同步通途状态
			$this->goods_data->update($id,['is_tongtu'=>1]);
			$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
		}
	}

	/**
	 * 删除
	 */
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
	 * 同步spu
	 */
	public function synchronization(){
		$goods_apply_id = input('id');
		if($goods_apply_id){
			$ret = $this->goods_distribution_facade->synchronization($goods_apply_id); //同步

			if($ret){ //成功
				$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->goods_distribution_facade->get_error());
			}
		}
	}

	/**
	 * 导入保存
	 */
	public function addexcel_save(){

		//读取excel
		$file_name = input('file_name');
		if(!$file_name){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'请上传文件');
		}

		$data = $this->_excel_common($file_name);//读取excel文件

		$goods = [];
		$error = [];
		//整理数组
		foreach($data as $value){
			if(!$value['A'] ||!$value['B'] ||!$value['C'] ||!$value['D'] || !is_numeric($value['E']) || !is_numeric($value['X']) || !$value['R'] || !$value['S'] ||  !$value['U'] ||  !$value['T']){
				$value['Y'] = '必填项为空,或起批量、类别ID不为数字';
				$error[] = $value;
			}else{

				/*$goods[$value['A']]['code'] = $value['A'];
				$goods[$value['A']]['name'] = $value['B'];
				$goods[$value['A']]['name_en'] = $value['C'];
				$goods[$value['A']]['source_address'] = $value['D']; //货源地址
				$goods[$value['A']]['batch_quantity'] = $value['E']; //货源地址
				$goods[$value['A']]['launch_area'] = $value['F']; //投放区域
				$goods[$value['A']]['is_battery'] = $value['G']?1:0; //是否带电池 0否 1是
				$goods[$value['A']]['is_imitation'] = $value['H']?1:0; //是否仿冒 0否 1是
				$goods[$value['A']]['is_liquid'] = $value['I'];//是否液体 0否 1是
				$goods[$value['A']]['is_magnetism'] = $value['J'];//是否带磁 0否 1是
				$goods[$value['A']]['is_powder'] = $value['K'];//是否粉末 0否 1是
				$goods[$value['A']]['is_goods'] = $value['L']; //是否有货 0无 1有
				$goods[$value['A']]['voltage'] = $value['M']; //电压
				$goods[$value['A']]['plug_type'] = $value['N'];//插头类型
				$goods[$value['A']]['is_pack'] = $value['O'];//是否有独立包装 0否 1是
				$goods[$value['A']]['language'] = $value['P'];//支持语言(多语言用,号分隔)
				$goods[$value['A']]['remarks'] = $value['Q'];//备注


				$goods[$value['A']]['sku_list'][] = [
					'code'=>$value['R'],
					'norms'=>$value['S'],
					'weight'=>$value['T'],
					'price'=>$value['U'],
					'size'=>$value['V'],
					'remarks'=>$value['W']
				];*/

				//查询spu是否存在
				$spu_info = $this->goods_data->find(['code'=>$value['A']]);

				$goods = [];
				$goods = [
					'code' => $value['A'],
					'name' => $value['B'],
					'name_en' => $value['C'],
					'source_address' => $value['D'],//货源地址
					'batch_quantity' => $value['E'], //货源地址
					'launch_area' => $value['F'], //投放区域
					'is_battery' => $value['G']?1:0, //是否带电池 0否 1是
					'is_imitation' => $value['H']?1:0, //是否仿冒 0否 1是
					'is_liquid' => $value['I'],//是否液体 0否 1是
					'is_magnetism' => $value['J'],//是否带磁 0否 1是
					'is_powder' => $value['K'],//是否粉末 0否 1是
					'is_goods' => $value['L'], //是否有货 0无 1有
					'voltage' => $value['M'], //电压
					'plug_type' => $value['N'],//插头类型
					'is_pack' => $value['O'],//是否有独立包装 0否 1是
					'language' => $value['P'],//支持语言(多语言用,号分隔)
					'remarks'=>$value['Q'],
					'category_id'=>$value['X'],//类别ID
					'sku' => [
						'code' => $value['R'],
						'norms' => $value['S'],
						'weight' => $value['T'],
						'price' => $value['U'],
						'size' => $value['V'],
						'remarks' => $value['W']
					],
				];

				$ret = $this->goods_excel_goods->add_excel($this->admin['id'],$goods);
				if(!$ret){
					$value['Y'] = $this->goods_excel_goods->get_error();
					$error[] = $value;
				}
			}
		}

		if(count($error)){
			//写入文件
			log_message('addexcel_save',json_encode($error),true);
			//保存到数据库
			$this->excel_error_log_data->store(['name'=>'导入失败','datetime'=>date('Y-m-d H:i:s'),'content'=>json_encode($error),'u_id'=>$this->admin['id']]);
			$this->output->ajax_return(AJAX_RETURN_FAIL,'导入失败，请下载失败日志');
		}

		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'OK');
	}

	/**
	 * 查询错误日志列表
	 */
	public function error_log(){

		$list = $this->excel_error_log_data->get_field_by_where(['id','name','datetime','u_id'],[],true);

		$this->load->view ( '', ['list'=>$list] );
	}


	public function error_dow($id = 0){

		$info = $this->excel_error_log_data->get_info($id);

		$data = json_decode($info['content']);

		$this->_exportExcel($data,$info['name'].$info['datetime'],26);

	}
}