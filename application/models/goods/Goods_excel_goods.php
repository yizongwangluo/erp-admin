<?php

class Goods_excel_goods extends \Application\Component\Common\IFacade
{
    public function __construct ()
    {

        parent::__construct ();
        $this->load->model ( 'data/goods_data' );
        $this->load->model ( 'data/goods_sku_data' );
    }

    /**
     * 同步
     * @param int $u_id
     * @param array $arr
     * @return bool
     */
    public function add_excel($u_id = 0,$arr = []){

        if(!$arr['code']){
            $this->set_error('请填写产品编码');return false;
        }

        $sku_info = $arr['sku'];
        unset($arr['sku']);

        $arr['u_id'] = $u_id;

        $spu_id = $this->goods_data->find(['code'=>$arr['code']])['id'];
        if($spu_id){ //修改
            $ret = $this->goods_data->update($spu_id,$arr);
            if(!$ret){
                $this->set_error('更新spu失败，请稍后重新提交');return false;
            }
//            $this->set_error('该产品已存在，无法在导入时更新，请手动更新');return false;
        }else{ //添加
            $spu_id = $this->goods_data->store($arr);
            if(!$spu_id){
                $this->set_error('添加spu失败，请稍后重新添加');return false;
            }
        }

        $sku_info['spu_id'] = $spu_id;
        $sku_id = $this->goods_sku_data->find(['code'=>$sku_info['code']])['id'];
        if($sku_id){ //修改
            $ret = $this->goods_sku_data->update($sku_id,$sku_info);
            if(!$ret){
                $this->set_error('更新sku失败，请稍后重新提交');return false;
            }
//            $this->set_error('该SKU已存在，无法在导入时更新，请手动更新');return false;
        }else{ //添加
            $sku_id = $this->goods_sku_data->store($sku_info);
            if(!$sku_id){
                $this->set_error('添加sku失败，请稍后重新添加');return false;
            }
        }

        return true;
    }

}