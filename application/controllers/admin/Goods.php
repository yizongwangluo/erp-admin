<?php

/**
 * 商品列表
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */

use  Application\Component\Concrete\MaBangApi\ErpApiFactory;

class Goods extends \Application\Component\Common\AdminPermissionValidateController
{

	protected  $is_yeti = null;
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
		$this->load->model ( 'data/goods_warehouse_data' );

		$this->is_yeti = ["非液体","液体(化妆品)","非液体(化妆品)","液体(非化妆品)"];

	}

	/**
	 * 搜索条件判断
	 * @param array $input
	 */
	public function goods_search($input = []){
		if($input['a']=='sku_code_mh' && strlen($input['name'])<6){
//			echo '<script type="text/javascript">alert("'.html_escape('sku编码模糊搜索 - 搜索字符长度不能小于6').'");history.back();</script>';
//			exit();
			show_error('sku编码模糊搜索 - 搜索字符长度不能小于6');
//			$this->output->ajax_return(AJAX_RETURN_FAIL,);
		}
	}

	/**
	 * 商品列表
	 */
	public function index(){
		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

//		$this->goods_search($input);

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
		$warehouse_list = $this->goods_warehouse_data->lists();

		$this->load->view ('',['info'=>$info,'category_list'=>$category_list,'warehouse_list'=>$warehouse_list]);
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
	 * 同步商品到马帮 批量
	 */
	public function add_sku_mabang(){

		$id = input('id');

		//查询该spu
		$info = $this->goods_data->get_info($id);
		if(!$info){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'系统繁忙，请稍后重试！');
		}
		$sku_list = $this->goods_sku_data->lists(['spu_id'=>$id]);
		if(empty($sku_list)){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'未查询到sku,请重试！');
		}

		$this->erpApi = new ErpApiFactory();

		foreach($sku_list as $k=>$v){

			$v['name'] = $info['name']; //英文名
			$v['name_en'] = $info['name_en']; //英文名
			$v['dc_name_en'] = $info['dc_name_en']; //报关英文名
			$v['dc_name'] = $info['dc_name']; //报关中文名
			$v['is_liquid'] = $info['is_liquid']; //报关中文名
			$v['is_battery'] = $info['is_battery']; //报关中文名
			$v['is_tort'] = $info['is_tort']; //报关中文名
			$v['is_magnetism'] = $info['is_magnetism']; //报关中文名
			$v['is_powder'] = $info['is_powder']; //报关中文名
			$v['supplier_name'] = $info['supplier_name']; //供应商
//			$v['className'] = $this->goods_category_data->get_info($info['category_id'])['name'];
			$v['warehouse_name'] = $this->goods_warehouse_data->get_info($info['warehouse_id'])['name'];
			$v['source_address'] = $v['source_address']?$v['source_address']:$info['source_address'];

			if(!$v['is_mabang']){ //新增

				$ret = $this->erpApi->add_stock($v);

				if($ret['code']=='000'){
					//修改同步马帮状态
					$this->goods_sku_data->update($v['id'],['is_mabang'=>1,'stockId'=>$ret['stockId']]);
				}else{
					$this->output->ajax_return(AJAX_RETURN_FAIL,$ret['message']);
				}
			}else{  //更新

				$ret = $this->erpApi->change_stock($v);

				if($ret['code']!='000'){
					$this->output->ajax_return(AJAX_RETURN_FAIL,$ret['message']);
				}
			}

		}

		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
	}

	/**
	 * 同步商品到马帮 单个
	 */
	public function add_sku_mabang_one(){

		$id = input('sku_id');

		//查询该spu
		$sku_info = $this->goods_sku_data->get_info($id);
		if(!$sku_info){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'未查询到该sku,请重试！');
		}
		$spu_info = $this->goods_data->get_info($sku_info['spu_id']);
		if(empty($spu_info)){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'未查询到对应商品，请稍后重试！');
		}

		$this->erpApi = new ErpApiFactory();

		$sku_info['name'] = $spu_info['name']; //英文名
		$sku_info['name_en'] = $spu_info['name_en']; //英文名
		$sku_info['dc_name_en'] = $spu_info['dc_name_en']; //报关英文名
		$sku_info['dc_name'] = $spu_info['dc_name']; //报关中文名
		$sku_info['is_liquid'] = $spu_info['is_liquid']; //报关中文名
		$sku_info['is_battery'] = $spu_info['is_battery']; //报关中文名
		$sku_info['is_tort'] = $spu_info['is_tort']; //报关中文名
		$sku_info['is_magnetism'] = $spu_info['is_magnetism']; //报关中文名
		$sku_info['is_powder'] = $spu_info['is_powder']; //报关中文名
		$sku_info['supplier_name'] = $spu_info['supplier_name']; //供应商
//			$sku_info['className'] = $this->goods_category_data->get_info($info['category_id'])['name'];
		$sku_info['warehouse_name'] = $this->goods_warehouse_data->get_info($spu_info['warehouse_id'])['name'];

		if(!$sku_info['is_mabang']){ //新增

			$ret = $this->erpApi->add_stock($sku_info);

			if($ret['code']=='000'){
				//修改同步马帮状态
				$this->goods_sku_data->update($sku_info['id'],['is_mabang'=>1,'stockId'=>$ret['stockId']]);
			}else{
				$this->output->ajax_return(AJAX_RETURN_FAIL,$ret['message']);
			}
		}else{  //更新

			$ret = $this->erpApi->change_stock($sku_info);

			if($ret['code']!='000'){
				$this->output->ajax_return(AJAX_RETURN_FAIL,$ret['message']);
			}
		}

		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');
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

		set_time_limit(0); //取消超时时间
		ini_set('memory_limit','2048M');

		//读取excel
		$file_name = input('file_name');
		if(!$file_name){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'请上传文件');
		}

		$data = $this->_excel_common($file_name,'AT');//读取excel文件

		$error = [];

		$spu_id = 0;
		//整理数组
		foreach($data as $value){

			if($value['A'] && $value['B']){ //判断是否是SPU

					//类别ID
					if($value['W']){
						$goods['category_id'] = $this->goods_category_data->get_cateid($value['V']);
					}

					$goods = [
							'code' => $value['A'],
							'name' => $value['B'],
							'name_en' => $value['C'],
							'volume' => $value['T'], //体积
							'remarks' => $value['V'], //备注
							'supplier_name' => $value['W'], //供应商
							'batch_quantity' => $value['X'],//最小采购
							'source_address' => $value['Y'],//采购链接
							'purchase_remarks'=>$value['Z'],//采购备注
							't_status' => $value['AB'],//状态
							'poperty_label' => $value['AD'],//特性标签
							'dc_name' => $value['AG'], //中文报关名
							'dc_name_en' => $value['AH'], //英文报关名
							'pack_cost' => $value['AK'],//包装成本
							'pack_weight' => $value['AL'],//包装重量
							'pack_volume' => $value['AM'],//体积 带包装
							'img' => $value['AU'],//产品图片
							'u_id' => $this->admin['id'] //操作人
					];

					$ret = $this->goods_data->excelSave($goods);
					$spu_id = $ret;
					if(!$ret){
						$spu_id = 0;
						$value['AV'] = $this->goods_excel_goods->get_error();
						$error[] = $value;
					}

			}elseif($value['J']){ //判断是否是sku
				if($spu_id){
					$sku = [
					 'norms_name'=>$value['D'], //属性名1
					 'norms'=>$value['E'],//属性值1
					 'norms_name1'=>$value['F'],//属性名2
					 'norms1'=>$value['G'],//属性值2
					 'norms_name2'=>$value['H'],//属性名3
					 'norms2'=>$value['I'],//属性值3
					 'code'=>$value['J'], //SKU属性编号
					 'spu_id'=>$spu_id,
					 'weight'=>$value['K'],//产品重量
					 'price'=>$value['L'],
					 'alias'=>$value['M'],
					 'source_address'=>$value['Y'],//采购链接
					 'img'=>$value['AU'],//产品图片
					 'purchase_remarks'=>$value['Z']//采购备注
					];

					$ret = $this->goods_sku_data->excelSave($sku);
					if(!$ret){
						$value['AV'] = $this->goods_sku_data->get_error();
						$error[] = $value;
					}

				}else{
					$value['AV'] = '没有对应的SPU数据';
					$error[] = $value;
				}
			}
		}

		if(count($error)){
			//写入文件
			$err_name = $this->_localExcel($error,'error',46);
			//保存到数据库
			$this->excel_error_log_data->store(['name'=>'导入失败','datetime'=>date('Y-m-d H:i:s'),'content'=>$err_name,'u_id'=>$this->admin['id']]);
			$this->output->ajax_return(AJAX_RETURN_FAIL,'导入失败，请下载失败日志');
		}

		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'OK');
	}

	/**
	 * 查询错误日志列表
	 */
	public function error_log(){

		$list = $this->excel_error_log_data->lists(['type'=>1],true);

		$this->load->view ( '', ['list'=>$list] );
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

		set_time_limit(0);

		$input = $this->input->get();
		$page = max(1,$input['page']);
		unset($input['page']);

//		$this->goods_search($input);

		$list = $this->goods_data->get_list($this->admin['id'],$input,$page,17,true);

		$data = $this->goods_excel_temp_all($list); //导出模板

		$this->exportCsv($data,'商品列表');
	}

	/**
	 * 导出全部 - mabang
	 */
	public function daochu_all_mb(){

		$page = input('page');
		$page = $page?$page:1;
		$limit = input('limit');
		$limit = min($limit,5000);

		set_time_limit(0);

		//获取商品详情
		$spu_list = $this->goods_data->lists();
		$spu_list = array_column($spu_list,null,'id');
		$warehouse_list = $this->goods_warehouse_data->lists();
		$warehouse_list = array_column($warehouse_list,null,'id');
		$category_list = $this->goods_category_data->lists();
		$category_list = array_column($category_list,null,'id');
		$sku_list = $this->goods_sku_data->lists_page([],['id','desc'],$page,$limit);

		$data = $this->goods_excel_temp_mb($spu_list,$sku_list['data'],$warehouse_list,$category_list);
//		$data = $this->goods_excel_temp_mb_update($spu_list,$sku_list['data'],$warehouse_list,$category_list);

		$this->_exportExcel($data,'商品列表-'.$page,56);
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
	 * 导出马帮
	 */
	public function daochu_mb(){

		$ids = input('ids');
		$ids = trim($ids,',');

		$spu_list = $this->goods_data->get_list_inids($ids);
		$spu_list = array_column($spu_list,null,'id');
		$warehouse_list = $this->goods_warehouse_data->lists();
		$warehouse_list = array_column($warehouse_list,null,'id');
		$sku_list = $this->goods_sku_data->get_list_inspuids($ids);
		$category_list = $this->goods_category_data->lists();
		$category_list = array_column($category_list,null,'id');

		$data = $this->goods_excel_temp_mb_update($spu_list,$sku_list,$warehouse_list,$category_list); //导出模板

		$this->_exportExcel($data,'商品列表',56);
	}


	/**
	 * 导出模板
	 * @param array $spu_list
	 * @param array $sku_list
	 * @param array $warehouse_list
	 * @return array
	 */
	private function goods_excel_temp_mb($spu_list = [],$sku_list = [],$warehouse_list = [],$category_list = []){

		$data = [];

		$data['heard'] = ["*库存sku编号","*库存sku名称","库存sku英文名称","sku状态(自动创建、等待开发、正常销售)",
				"主SKU","成本价","最新采购价","售价","品牌","商品目录","申报品名(中文)","申报品名(英文)",
				"商品自定义分类(多个用英文';'号隔开)","商品仓库","仓位","仓库成本价","库存","商品重量","供应商",
				"上次采购价格","最低采购价格","供应商商品网址","销售员(多个用','隔开)","美工","采购员","采购天数",
				"最小采购数量","最大采购数量","库存警戒天数","警戒库存","配货员","质检标准","包材","可包装个数",
				"原厂SKU","是否带电池（0/1）","库存图片地址","商品备注","销售备注","采购备注","虚拟sku(多个用英文';'分割)",
				"商品尺寸长(cm)","商品尺寸宽(cm)","商品尺寸高(cm)","体积重系数","开发员","申报价格($)",
				"液体化妆品（非液体、液体(化妆品)、非液体(化妆品)、液体(非化妆品)）","是否侵权（0/1）","是否粉末（0/1）",
				"是否带磁（0/1）","采购方式（分仓采购/联合采购）","报关编码","创建时间(2018/12/18 23:59:59)"];

		foreach($sku_list as $k=>$v){

			$data[$k][] = $v['code'];//库存sku编号
			$data[$k][] = $spu_list[$v['spu_id']]['name'].'-'.$v['norms'].$v['norms1'];//库存sku名称
			$data[$k][] = $spu_list[$v['spu_id']]['name_en'];//库存sku英文名称
			$data[$k][] = '正常销售';//sku状态(自动创建、等待开发、正常销售)
			$data[$k][] = '';//主SKU
			$data[$k][] = $v['price'];//成本价
			$data[$k][] = $v['price'];//最新采购价
			$data[$k][] = 0;//售价
			$data[$k][] = '';//品牌
			$data[$k][] = '';//商品目录
			$data[$k][] = $spu_list[$v['spu_id']]['dc_name'];//申报品名(中文)
			$data[$k][] = $spu_list[$v['spu_id']]['dc_name_en'];//申报品名(英文)
			$data[$k][] = $category_list[$spu_list[$v['spu_id']]['category_id']]['name'];//商品自定义分类(多个用英文';'号隔开)
			$data[$k][] = $warehouse_list[$spu_list[$v['spu_id']]['warehouse_id']]['name'];//商品仓库
			$data[$k][] = '';//仓位
			$data[$k][] = '';//仓库成本价
			$data[$k][] = '';//库存
			$data[$k][] = $v['weight'];//商品重量
			$data[$k][] = $spu_list[$v['spu_id']]['supplier_name'];//供应商
			$data[$k][] = $v['price'];//上次采购价格
			$data[$k][] = $v['price'];//最低采购价格
			$data[$k][] = $spu_list[$v['spu_id']]['source_address'];//供应商商品网址
			$data[$k][] = '';//销售员(多个用","隔开)
			$data[$k][] = '';//美工
			$data[$k][] = '';//采购员
			$data[$k][] = $v['cycle'];//采购天数
			$data[$k][] = $spu_list[$v['spu_id']]['batch_quantity'];//最小采购数量
			$data[$k][] = '';//最大采购数量
			$data[$k][] = '';//库存警戒天数
			$data[$k][] = '';//警戒库存
			$data[$k][] = '';//配货员
			$data[$k][] = '';//质检标准
			$data[$k][] = '';//包材
			$data[$k][] = '';//可包装个数
			$data[$k][] = '';//原厂SKU
			$data[$k][] = $spu_list[$v['spu_id']]['is_battery'];//是否带电池（0/1）
			$data[$k][] = $v['img'];//库存图片地址
			$data[$k][] = $v['remarks'];//商品备注
			$data[$k][] = '';//销售备注
			$data[$k][] = '';//采购备注
//			$data[$k][] = $v['alias'];//虚拟sku(多个用英文';'分割)
			$data[$k][] = '';//虚拟sku(多个用英文';'分割)

			$size = explode('*',$v['size']);

			$data[$k][] = $size[0];//商品尺寸长(cm)
			$data[$k][] = $size[1];//商品尺寸宽(cm)
			$data[$k][] = $size[2];//商品尺寸高(cm)
			$data[$k][] = '';
			$data[$k][] = '';
			$data[$k][] = $v['price'];//申报价格($)
			$data[$k][] = $this->is_yeti[$spu_list[$v['spu_id']]['is_liquid']];//液体化妆品（非液体、液体(化妆品)、非液体(化妆品)、液体(非化妆品)）
			$data[$k][] = $spu_list[$v['spu_id']]['is_tort'];//是否侵权（0/1）
			$data[$k][] = $spu_list[$v['spu_id']]['is_powder'];//是否粉末（0/1）
			$data[$k][] = $spu_list[$v['spu_id']]['is_magnetism'];//是否带磁（0/1）
			$data[$k][] = '';//采购方式（分仓采购/联合采购）
			$data[$k][] = '';//报关编码
			$data[$k][] = date('Y/m/d H:i:s',$spu_list[$v['spu_id']]['edittime']);//创建时间(2018/12/18 23:59:59)创建时间(2018/12/18 23:59:59)
		}

		return $data;
	}

	/**
	 * 导出模板 - 更新
	 * @param array $spu_list
	 * @param array $sku_list
	 * @param array $warehouse_list
	 * @return array
	 */
	private function goods_excel_temp_mb_update($spu_list = [],$sku_list = [],$warehouse_list = [],$category_list = []){

		$data = [];

		$data['heard'] = ['*库存sku编号','sku状态(自动创建、等待开发、正常销售、商品清仓、停止销售)','库存sku名称','主SKU','库存sku英文名称',
				'成本价','品牌','售价','最新采购价','商品父目录','商品子目录','申报品名(中文)','申报品名(英文)','商品仓库','仓位','仓库成本价',
				'商品重量区间(最小值/最大值用英文";"号隔开)','商品重量','供应商','最低采购价格','供应商商品网址','美工','销售员(多个用","隔开)',
				'采购员','采购天数','最小采购数量','最大采购数量','库存警戒天数','警戒库存','配货员','包材','可包装个数','原厂SKU',
				'是否带电池(0/1)','库存图片地址','产品细节图片地址(多个用英文“;”分割)','销售备注','商品备注','采购备注','商品尺寸长(cm)',
				'商品尺寸宽(cm)','商品尺寸高(cm)','体积重系数','虚拟sku(多个用英文\';\'分割)','开发员','申报价格($)',
				'液体化妆品（非液体、液体(化妆品)、非液体(化妆品)、液体(非化妆品)）','是否赠品（0/1）（0代表否，1代表是）',
				'是否粉末（0/1）','是否带磁（0/1）','是否侵权（0/1）','采购方式（分仓采购/联合采购）','报关编码','自定义分类(多个用英文\';\'号隔开)',
				'质检标准','创建时间(2018/12/18 23:59:59)'];

		foreach($sku_list as $k=>$v){

			$data[$k][] = $v['code'];//库存sku编号
			$data[$k][] = '正常销售';//sku状态(自动创建、等待开发、正常销售)
			$data[$k][] = $spu_list[$v['spu_id']]['name'].'-'.$v['norms'].$v['norms1'];//库存sku名称
			$data[$k][] = '';//主SKU
			$data[$k][] = $spu_list[$v['spu_id']]['name_en'];//库存sku英文名称
			$data[$k][] = $v['price'];//成本价
			$data[$k][] = '';//品牌
			$data[$k][] = $v['price'];//售价
			$data[$k][] = $v['price'];//最新采购价
			$data[$k][] = '';//商品父目录
			$data[$k][] = '';//商品子目录
			$data[$k][] = $spu_list[$v['spu_id']]['dc_name'];//申报品名(中文)
			$data[$k][] = $spu_list[$v['spu_id']]['dc_name_en'];//申报品名(英文)
			$data[$k][] = $warehouse_list[$spu_list[$v['spu_id']]['warehouse_id']]['name'];//商品仓库
			$data[$k][] = '';//仓位
			$data[$k][] = '';//仓库成本价
			$data[$k][] = '';//商品重量区间(最小值/最大值用英文";"号隔开)
			$data[$k][] = $v['weight'];//商品重量
			$data[$k][] = $spu_list[$v['spu_id']]['supplier_name'];//供应商
			$data[$k][] = $v['price'];//最低采购价格
			$data[$k][] = $spu_list[$v['spu_id']]['source_address'];//供应商商品网址
			$data[$k][] = '';//美工
			$data[$k][] = '';//销售员(多个用","隔开)
			$data[$k][] = '';//采购员
			$data[$k][] = $v['cycle'];//采购天数
			$data[$k][] = $spu_list[$v['spu_id']]['batch_quantity'];//最小采购数量
			$data[$k][] = '';//最大采购数量
			$data[$k][] = '';//库存警戒天数
			$data[$k][] = '';//警戒库存
			$data[$k][] = '';//配货员
			$data[$k][] = '';//包材
			$data[$k][] = '';//可包装个数
			$data[$k][] = '';//原厂SKU
			$data[$k][] = $spu_list[$v['spu_id']]['is_battery'];//是否带电池（0/1）
			$data[$k][] = $v['img'];//库存图片地址
			$data[$k][] = '';//产品细节图片地址(多个用英文“;”分割)
			$data[$k][] = '';//销售备注
			$data[$k][] = $v['remarks'];//商品备注
			$data[$k][] = '';//采购备注
			$size = explode('*',$v['size']);
			$data[$k][] = $size[0];//商品尺寸长(cm)
			$data[$k][] = $size[1];//商品尺寸宽(cm)
			$data[$k][] = $size[2];//商品尺寸高(cm)
			$data[$k][] = '';//体积重系数
			$data[$k][] = str_replace(',',';',$v['alias']);//虚拟sku(多个用英文';'分割)
//			$data[$k][] = '';//虚拟sku(多个用英文';'分割)
			$data[$k][] = '';//开发员
			$data[$k][] = '';//申报价格($)
			$data[$k][] = $this->is_yeti[$spu_list[$v['spu_id']]['is_liquid']];//液体化妆品（非液体、液体(化妆品)、非液体(化妆品)、液体(非化妆品)）
			$data[$k][] = 0;//是否赠品（0/1）（0代表否，1代表是）
			$data[$k][] = $spu_list[$v['spu_id']]['is_powder'];//是否粉末（0/1）
			$data[$k][] = $spu_list[$v['spu_id']]['is_magnetism'];//是否带磁（0/1）
			$data[$k][] = $spu_list[$v['spu_id']]['is_tort'];//是否侵权（0/1）
			$data[$k][] = '';//采购方式（分仓采购/联合采购）
			$data[$k][] = '';//报关编码
			$data[$k][] = $category_list[$spu_list[$v['spu_id']]['category_id']]['name'];//商品自定义分类(多个用英文';'号隔开)
			$data[$k][] = '';//质检标准
			$data[$k][] = date('Y/m/d H:i:s',$spu_list[$v['spu_id']]['edittime']);//创建时间(2018/12/18 23:59:59)创建时间(2018/12/18 23:59:59)
		}

		return $data;
	}


	/**
	 * 导出模板 daochu_all
	 * @param array $spu_list
	 * @return array
	 */
	private function goods_excel_temp_all($spu_list = []){

		$data = [];
		$data['heard'] = ["ID","产品图片","产品名","SKU","SKU别名","规格名1","规格值1","规格名2","规格值2","采购价（元）","重量（克）"];
		$i=0;
		foreach($spu_list as $k=>$v){
			$i++;
			$data[$i][] = $v['id'];
			$data[$i][] = $v['img'];
			$data[$i][] = $v['name'];
			$data[$i][] = $v['sku_code'];
			$data[$i][] = $v['alias'];
			$data[$i][] = $v['norms_name'];
			$data[$i][] = $v['norms'];
			$data[$i][] = $v['norms_name1'];
			$data[$i][] = $v['norms1'];
			$data[$i][] = $v['price'];
			$data[$i][] = $v['weight'];
		}
		return $data;
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
			$data[$i][] = $v['poperty_label'];//特性标签
			$data[$i][] = $v['name'];//中文配货名称
			$data[$i][] = $v['name_en'];//英文配货名称
			$data[$i][] = $v['dc_name'];//中文报关名
			$data[$i][] = $v['dc_name_en'];//英文报关名
			$data[$i][] = '';//包装材料名称
			$data[$i][] = $v['pack_cost'];//包装成本(CNY)
			$data[$i][] = $v['pack_weight'];//包装重量(g)
			$data[$i][] = $v['pack_volume'];//包装尺寸(长*宽*高)CM
			$data[$i][] = $v['img'];//产品首图
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