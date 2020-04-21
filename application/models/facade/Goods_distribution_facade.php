<?php

/**
 * 商品同步
 * User: blueidea
 * Date: 2016/12/23
 * Time: 9:54
 */

class Goods_distribution_facade extends \Application\Component\Common\IFacade {

    function __construct() {
        parent::__construct();
        $this->load->model('data/goods_data');
        $this->load->model('data/goods_apply_data');
        $this->load->model('data/goods_sku_data');
        $this->load->model('data/goods_sku_apply_data');
    }

    /**
     *
     */
    public function synchronization($goods_apply_id = 0){

        //查询是否存在该申请表数据
        $apply_info = $this->goods_apply_data->get_info($goods_apply_id);
        if(!$apply_info){
            $this->set_error('未查询到该申请表数据');return false;
        }

        //添加（修改）正式表spu
        $id = $this->goods_data->synchronization($apply_info);
        if(!$id){
            $this->set_error('同步spu出错，请重新同步！');return false;
        }elseif($id == 'set'){
            $this->set_error('spu已存在！');return false;
        }else{
            //查询申请表sku列表
            $sku_list = $this->goods_sku_apply_data->lists(['spu_id'=>$goods_apply_id,'is_real'=>0]);

            //判断新增sku是否重复
            $skus = [];
            foreach($sku_list as $value){
                array_push($skus,$value['code']);
                $value['spu_id'] = $id;
            }
            if (count($skus) != count(array_unique($skus))) {
                $this->goods_data->del($id);
                $re = $this->goods_sku_apply_data->edit_status($goods_apply_id,['status'=>2,'is_real'=>0]);
                $this->set_error('新增sku编码重复,请检查！');return false;
            }

            foreach($sku_list as $value){
                $sku_info = $value;
                $sku_info['spu_id'] = $id;
                unset($sku_info['id']);
                unset($sku_info['status']);
//                unset($sku_info['u_id']);
                unset($sku_info['is_real']);
                $ret = $this->goods_sku_data->synchronization($sku_info);
                if(!$ret){
                    $this->goods_data->del($id);
                    $this->set_error('sku已存在，请检查后重新同步！');
                    return false;
                }
            }
        }

        return true;
    }

}