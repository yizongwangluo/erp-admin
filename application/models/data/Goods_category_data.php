<?php

class Goods_category_data extends \Application\Component\Common\IData
{

    /**
     * 添加
     * @param array $input
     * @return bool|int
     */
    public function add($input = []){

        if(empty($input['name'])){
            $this->set_error('请填写类别名称！');return false;
        }

        if($this->removal(['name'=>$input['name']])){
            $this->set_error('该类别名称已存在，无法重复添加');return false;
        }

        $data = [];

        $data['name'] = $input['name'];
        $data['status'] = $input['status']?$input['status']:1;

        return $this->store($data);

    }

    /**
     * 修改
     * @param int $id
     * @param array $input
     * @return bool|int
     */
    public function edit($id = 0,$input = []){

        if(empty($input['name'])){
            $this->set_error('请填写类别名称！');return false;
        }

        if($this->removal(['id'=>$id,'name'=>$input['name']])){
            $this->set_error('该类别名称已存在，无法重复添加');return false;
        }

        $data = [];

        $data['name'] = $input['name'];
        $data['status'] = $input['status']?$input['status']:1;

        return $this->update($id,$data);
    }

    /**
     * 判断是否已存在该数据
     * @param array $input
     * @return bool
     */
    public function removal($input = array()){

        $data = [];

        $data['id !=']      = $input['id'] ? $input['id']:'';
        $data['name']    	 = $input['name'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
    }

}