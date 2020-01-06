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
        }

        //查询申请表sku列表
        $sku_list = $this->goods_sku_apply_data->lists(['spu_id'=>$goods_apply_id,'status'=>1,'is_real'=>0]);

        foreach($sku_list as $value){
            $sku_info = $value;
            $sku_info['spu_id'] = $id;
            unset($sku_info['id']);
            unset($sku_info['status']);
            unset($sku_info['u_id']);
            unset($sku_info['is_real']);
            $ret = $this->goods_sku_data->synchronization($sku_info);
            if(!$ret){
                $this->set_error('同步sku出错，请重新同步！');return false;
            }
        }
        return true;
    }

}