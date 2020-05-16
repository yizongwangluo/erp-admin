<?php

/**
 * 商品列表
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */

use  Application\Component\Concrete\TongTuApi\ErpApiFactory;

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

		$data['page_html'] = create_page_html ( '?', $data['total'] );

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
		$data['productName'] = $info['name'];//商品名称
		$data['productPackingName'] = $info['name'];//中文配货名
		$data['productPackingEnName'] = $info['name_en']; //英文配货名
		$data['declareCnName'] = $info['dc_name'];//中文报关名
		$data['declareEnName'] = $info['dc_name_en'];//英文报关名
		$data['productStatus'] = "1";

		$data['suppliers'][0]['minPurchaseQuantity'] = (int)$info['batch_quantity'];
		$data['suppliers'][0]['purchaseRemark'] = $info['remarks'];
		$data['suppliers'][0]['supplierName'] = $info['supplier_name'];

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

			$this->erpApi = new ErpApiFactory();

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

		$data = $this->_excel_common($file_name,'AO');//读取excel文件
		$error = [];

		/*foreach($data as $k=>$v){ //spu/sku分组
			if($v['A']){
				$goods[count($goods)] = $v;
			}else{
				$goods[count($goods)-1]['sku_list'][] = $v;
			}
		}*/


		$spu_id = 0;
		//整理数组
		foreach($data as $value){

			if($value['A'] && $value['B']){ //判断是否是SPU

				//查询spu是否存在
				if($this->goods_data->find(['code'=>$value['A']])){
					$value['AP'] = 'SPU已存在';
					$error[] = $value;
				}else{
					$goods = [];

					//类别ID
					if($value['W']){
						$goods['category_id'] = $this->goods_category_data->get_cateid($value['V']);
					}

					$goods = [
							'code' => $value['A'],
							'name' => $value['B'],
							'name_en' => $value['C'],
							'volume' => $value['O'], //体积
							'remarks' => $value['Q'], //备注
							'supplier_name' => $value['R'], //供应商
							'batch_quantity' => $value['S'],//最小采购
							'source_address' => $value['T'],//采购链接
							't_status' => $value['W'],//采购链接
							'dc_name' => $value['AB'], //中文报关名
							'dc_name_en' => $value['AC'], //英文报关名
							'pack_cost' => $value['AF'],//包装成本
							'pack_weight' => $value['AG'],//包装重量
							'pack_volume' => $value['AH'],//体积 带包装
							'u_id' => $this->admin['id'] //操作人
					];

					$ret = $this->goods_data->add($goods);
					$spu_id = $ret;
					if(!$ret){
						$spu_id = 0;
						$value['AP'] = $this->goods_excel_goods->get_error();
						$error[] = $value;
					}
				}

			}elseif($value['H']){
				if($spu_id){

					$sku = [];

					$sku = [
					 'norms_name'=>$value['D'],
					 'norms'=>$value['E'],
					 'norms_name1'=>$value['F'],
					 'norms1'=>$value['G'],
					 'code'=>$value['H'],
					 'spu_id'=>$spu_id,
					 'weight'=>$value['I'],
					 'price'=>$value['J'],
					 'alias'=>$value['K'],
					 'source_address'=>$value['T']
					];

					if($sku['alias']){ //别名判断
						if(in_array($sku['code'],explode(',',$sku['alias']))){
							$value['AP'] = 'sku编码与sku别名重复';
							$error[] = $value;
							continue;
						}

						if(!model('data/goods_sku_data')->get_only($sku,true)){ //判断主表
							$value['AP'] = 'sku别名已存在或与sku编码冲突';
							$error[] = $value;
							continue;
						}

						if(!model('data/goods_sku_apply_data')->get_only($sku,true)){ //判断申请表
							$value['AP'] = 'sku别名已存在或与sku编码冲突';
							$error[] = $value;
							continue;
						}

					}

					$ret = $this->goods_sku_data->add($sku);
					if(!$ret){
						$value['AP'] = $this->goods_sku_data->get_error();
						$error[] = $value;
					}

				}else{
					$value['AP'] = '没有对应的SPU数据';
					$error[] = $value;
				}
			}else{
				$value['AP'] = '数据不完整';
				$error[] = $value;
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

		$list = $this->excel_error_log_data->get_field_by_where(['id','name','datetime','u_id'],['type'=>1],true);

		$this->load->view ( '', ['list'=>$list] );
	}


	public function error_dow($id = 0){

		$info = $this->excel_error_log_data->get_info($id);

		$data = json_decode($info['content']);

		$this->_exportExcel($data,$info['name'].$info['datetime'],28);

	}

	/**
	 * 删除
	 */
	public function error_log_del(){

		$id = input('id');

		if($id){
			$ret = $this->excel_error_log_data->delete($id);

			if($ret){ //成功
				$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$this->excel_error_log_data->get_error());
			}
		}
	}

	/**
	 * 导出全部
	 */
	public function daochu_all(){

		//获取商品详情
		$spu_list = $this->goods_data->lists();
		$category_list = $this->goods_category_data->lists();
		$category_list = array_column($category_list,null,'id');

		foreach($spu_list as $k=>$v){
			$spu_list[$k]['sku_list'] = $this->goods_sku_data->get_list_spuid($v['id']);
		}

		$data = $this->goods_excel_temp($spu_list,$category_list); //导出模板

		$this->_exportExcel($data,'商品列表',38);
	}

	public function daochu(){

		$ids = input('ids');
		$ids = trim($ids,',');

		//获取商品详情
		$spu_list = $this->goods_data->get_list_inids($ids);
		$category_list = $this->goods_category_data->lists();
		$category_list = array_column($category_list,null,'id');

		foreach($spu_list as $k=>$v){
			$spu_list[$k]['sku_list'] = $this->goods_sku_data->get_list_spuid($v['id']);
		}

		$data = $this->goods_excel_temp($spu_list,$category_list); //导出模板

		$this->_exportExcel($data,'商品列表',38);
	}

	/**
	 * 导出模板
	 * @param array $spu_list
	 * @return array
	 */
	private function goods_excel_temp($spu_list = [],$category_list = []){

		$data = [];
		$data['heard'] = ['SKU',
				'产品名称',
				'SKU别名',
				'属性名1',
				'属性值1',
				'属性名2',
				'属性值2',
				'SKU属性编号',
				'产品重量(g)',
				'采购单价',
				'SKU属性别名',
				'仓库名称1',
				'库存数量1',
				'货位1',
				'仓库名称2',
				'库存数量2',
				'货位2',
				'产品体积(长*宽*高)CM',
				'产品特点',
				'备注',
				'供应商名称',
				'最小采购量(MOQ)',
				'采购链接',
				'分类',
				'品牌',
				'特性标签',
				'中文配货名称',
				'英文配货名称',
				'中文报关名',
				'英文报关名',
				'包装材料名称',
				'包装成本(CNY)',
				'包装重量(g)',
				'包装尺寸(长*宽*高)CM',
				'产品首图',
				'业务开发员',
				'采购询价员',
				'采购员'
		];

		$i=0;
		foreach($spu_list as $k=>$v){
			$i++;
			$data[$i][] = $v['code'];//产品编码
			$data[$i][] = $v['name'];//产品名称
			$data[$i][] = '';//别名
			$data[$i][] = '';//属性名1
			$data[$i][] = '';//属性值1
			$data[$i][] = '';//属性名2
			$data[$i][] = '';//属性值2
			$data[$i][] = '';//属性编号
			$data[$i][] = '';//产品重量
			$data[$i][] = '';//采购单价
			$data[$i][] = '';//SKU属性别名
			$data[$i][] = '';//仓库名称1
			$data[$i][] = '';//库存数量1
			$data[$i][] = '';//货位1
			$data[$i][] = '';//仓库名称2
			$data[$i][] = '';//库存数量2
			$data[$i][] = '';//货位2
			$data[$i][] = $v['volume'];//产品体积(长*宽*高)CM
			$data[$i][] = '';//产品特点
			$data[$i][] = $v['remarks'];//备注
			$data[$i][] = $v['supplier_name'];//供应商名称
			$data[$i][] = $v['batch_quantity'];//最小采购量(MOQ)
			$data[$i][] = $v['source_address'];//采购链接
			$data[$i][] = $category_list[$v['category_id']]['name'];//分类
			$data[$i][] = '';//品牌
			$data[$i][] = '';//特性标签
			$data[$i][] = $v['name'];//中文配货名称
			$data[$i][] = $v['name_en'];//英文配货名称
			$data[$i][] = $v['dc_name'];//中文报关名
			$data[$i][] = $v['dc_name_en'];//英文报关名
			$data[$i][] = '';//包装材料名称
			$data[$i][] = $v['pack_cost'];//包装成本(CNY)
			$data[$i][] = $v['pack_weight'];//包装重量(g)
			$data[$i][] = $v['pack_volume'];//包装尺寸(长*宽*高)CM
			$data[$i][] = base_url($v['img']);//产品首图
			$data[$i][] = '';//业务开发员
			$data[$i][] = '';//采购询价员
			$data[$i][] = '';//采购员

			foreach($v['sku_list'] as $item){

				$i++;

				$data[$i][] = '';//产品编码
				$data[$i][] = '';//产品名称
				$data[$i][] = '';//别名
				$data[$i][] = $item['norms_name'];//属性名1
				$data[$i][] = $item['norms'];//属性值1
				$data[$i][] = $item['norms_name1'];//属性名2
				$data[$i][] = $item['norms1'];//属性值2
				$data[$i][] = $item['code'];//属性编号
				$data[$i][] = $item['weight'];//产品重量
				$data[$i][] = $item['price'];//采购单价
				$data[$i][] = $item['alias'];//SKU属性别名
				$data[$i][] = '';//仓库名称1
				$data[$i][] = '';//库存数量1
				$data[$i][] = '';//货位1
				$data[$i][] = '';//仓库名称2
				$data[$i][] = '';//库存数量2
				$data[$i][] = '';//货位2
				$data[$i][] = '';//产品体积(长*宽*高)CM
				$data[$i][] = '';//产品特点
				$data[$i][] = '';//备注
				$data[$i][] = '';//供应商名称
				$data[$i][] = '';//最小采购量(MOQ)
				$data[$i][] = '';//采购链接
				$data[$i][] = '';//分类
				$data[$i][] = '';//品牌
				$data[$i][] = '';//特性标签
				$data[$i][] = '';//中文配货名称
				$data[$i][] = '';//英文配货名称
				$data[$i][] = '';//中文报关名
				$data[$i][] = '';//英文报关名
				$data[$i][] = '';//包装材料名称
				$data[$i][] = '';//包装成本(CNY)
				$data[$i][] = '';//包装重量(g)
				$data[$i][] = '';//包装尺寸(长*宽*高)CM
				$data[$i][] = '';//产品首图
				$data[$i][] = '';//业务开发员
				$data[$i][] = '';//采购询价员
				$data[$i][] = '';//采购员
			}
		}
		return $data;
	}

}