<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Royalty_rules_data extends \Application\Component\Common\IData{

    /**
     * 添加
     * @param array $input
     * @return bool
     */
    public function add($input = []){

        if(empty($input['o_id'])){
            $this->set_error('请选择部门');return false;
        }
        if(!is_numeric($input['service_charge'])){
            $this->set_error('请填写手续费');return false;
        }
        if(!is_numeric($input['freight'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(!is_numeric($input['register_fee'])){
            $this->set_error('请填写挂号费');return false;
        }

        if($this->removal($input)){
            $this->set_error('该部门提成规则已存在，无法重复添加');return false;
        }

        unset($input['data_px']);
        unset($input['data_gmp']);

        return $this->store($input);
    }


    /**
     * 修改
     * @param int $id
     * @param array $input
     * @return bool
     */
    public function edit($id = 0,$input= []){

        if(empty($input['o_id'])){
            $this->set_error('请选择部门');return false;
        }
        if(!is_numeric($input['service_charge'])){
            $this->set_error('请填写手续费');return false;
        }
        if(!is_numeric($input['freight'])){
            $this->set_error('请填写SPU编码');return false;
        }
        if(!is_numeric($input['register_fee'])){
            $this->set_error('请填写挂号费');return false;
        }

        if($this->removal(['id'=>$id,'o_id'=>$input['o_id']])){
            $this->set_error('该部门提成规则已存在，无法重复添加');return false;
        }

        unset($input['data_px']);
        unset($input['data_gmp']);

        return $this->update($id,$input);

    }

    /**
     * 判断是否已存在该数据
     * @param array $input
     * @return bool
     */
    public function removal($input = array()){

        $data = [];

        $data['id !=']      = $input['id'] ? $input['id']:'';
        $data['o_id']    	 = $input['o_id'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
    }


}