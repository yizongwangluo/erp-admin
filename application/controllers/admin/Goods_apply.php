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

		$data['page_html'] = create_page_html ( '?', $data['total'] ,$limit);

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

		//判断字符长度
		$lens = 33;
		$lens_en = 50;
		if(strlen($input['name'])>$lens){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'产品名过长，请填写10字以内！');
		}
		if(strlen($input['name_en'])>$lens){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'产品英文名过长，请填写'.$lens_en.'字以内！');
		}
		if(strlen($input['dc_name'])>$lens){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'中文报关名过长，请填写10字以内！');
		}
		if(strlen($input['dc_name_en'])>$lens){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'英文报关名过长，请填写'.$lens_en.'字以内！');
		}
		//判断字符长度end

		if($id){ //修改
			if(!$input['code'] && $input['status']==1){ //编码未填写
				$this->output->ajax_return(AJAX_RETURN_FAIL,'请填写SPU编码');
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

		if($sku_info['alias']){

			if(!model('data/goods_sku_apply_data')->get_only($sku_info)){ //判断申请表
				$this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已存在或与sku编码冲突');
			}

			if(!model('data/goods_sku_data')->get_only($sku_info)){ //判断主表
				$this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已存在或与sku编码冲突');
			}
		}

		$id = $sku_info['id'];
		unset($sku_info['id']);

		if($id){ //修改
			$ret = $this->goods_sku_apply_data->update($id,$sku_info);
		}else{ //添加
			$sku_info['u_id'] = $this->admin['id'];
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

		$data['page_html'] = create_page_html ( '?', $data['total'],$limit );

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

		$alias = $input['alias'];
		$sku = $input['code'];

        $alias_name = explode(',',$alias);

		if(in_array($sku,$alias_name)){
            $this->output->ajax_return(AJAX_RETURN_FAIL,'sku编码与sku别名重复');
        }


		if($input['alias']){ //别名存在时判断
			if(!model('data/goods_sku_apply_data')->get_only($input)){ //判断申请表
				$this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已存在或与sku编码冲突');
			}

			if(!model('data/goods_sku_data')->get_only($input)){ //判断主表
				$this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已存在或与sku编码冲突');
			}
		}

		$id = $input['id'];
		unset($input['id']);

		if(!$id){

			//根据spuid获取用户ID
			$input['u_id'] = $this->goods_apply_data->get_uid_in_id($input['spu_id']);

			$ret =  $this->goods_sku_apply_data->add($input);

		}else{
			$ret =  $this->goods_sku_apply_data->update($id,$input);
		}

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

	/**
	 * 判断别名
	 */
    public function alias(){

		$input = $this->input->post();

		if($input['alias']){
			if(!model('data/goods_sku_apply_data')->get_only($input)){ //判断申请表
				$this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已存在或与sku编码冲突'); }

			if(!model('data/goods_sku_data')->get_only($input)){ //判断正式表
				$this->output->ajax_return(AJAX_RETURN_FAIL,'sku别名已存在或与sku编码冲突');}

		}
		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'ok');

	}

	/**
	 * 根据采购链接获取商品信息
	 */
	public function source_address_html(){

//		$source_address = $this->input->post('source_address');
		$source_address = 'http://www.erp.com/ceshi.html';
//		$source_address = 'https://detail.1688.com/offer/584198577642.html?spm=a26352.13672862.offerlist.47.12e642d42qYuAy';
		$html = catchData($source_address);
		if(!$html){
			$this->output->ajax_return(AJAX_RETURN_FAIL,'同步sku信息失败，请手动填写！');
		}

		$data = [];
		preg_match('/<title>([^<>]*)<\/title>/', $html, $title);
		//标题
		$data['title'] = $title[1];
		preg_match('/<img src="([^<>]*)60x60.jpg" alt=/', $html, $img);
		//图片
		$data['img'] = $img[1].'400x400.jpg';

		preg_match('/<a class="name has-tips " ([^<>]*)>([^<>]*)<\/a>/', $html, $gys);
		//供应商
		$data['gys'] = $gys[2];

		preg_match('/iDetailData =([^<>]*)( };)/', $html, $iDetailData);
		$iDetailData = $iDetailData[1].'}';
		$iDetailData =  preg_replace('@([\w_0-9]+):@', '"\1":', $iDetailData);
		$iDetailData =  preg_replace('["https"]', 'https', $iDetailData);
		$iDetailData = json_decode($iDetailData,true);
		/*preg_match('/skuMap:([^<>]*)},/', $html, $sku);
		$sku = $sku[1].'}';
		$sku = json_decode($sku,true);*/
		$norms_name = $iDetailData['sku']['skuProps'][0]['prop'];
		$imgs = array_column( $iDetailData['sku']['skuProps'][0]['value'],'imageUrl','name');
		$sku = $iDetailData['sku']['skuMap'];
		$i=1;
		foreach($sku as $key=>$value){

			$data['sku'][$i-1] = ['id'=>$i,
								'norms_name'=>$norms_name,
								'norms'=>$key,
								'price'=>$value['price']?$value['price']:$iDetailData['sku']['priceRange'][0][1],
								];

			if($imgs[$key]){
				$data['sku'][$i-1]['img'] = $imgs[$key];
			}else{
				$imgK = explode('&gt;',$key);
				$data['sku'][$i-1]['img'] = $imgs[$imgK[0]]?$imgs[$imgK[0]]:$data['img'];
			}

			$i++;
		}

		$data['sku_id'] = count($data['sku']);

		$this->output->ajax_return(AJAX_RETURN_SUCCESS,'Ok',$data);

	}

}