<?php

/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Order_synchro_data extends \Application\Component\Common\IData{

    public function get_shop_one(){

        $sql = 'select * from order_synchro ORDER BY new_time limit 1';
        $info = $this->db->query ( $sql )->row_array ();

        return $info;
    }


    public function edit_shop_time($id = 0,$time = ''){

        return $this->update($id,['new_time'=>$time]);

    }

    /**
     * 添加
     * @param int $id
     * @param array $input
     */
    public function edit_order_synchro($id = 0,$input = []){
        $data = [];
        $data['shop_url'] = $input['domain'];
        $data['key'] = $input['shop_api_key'];
        $data['password'] = $input['shop_api_pwd'];
        $data['shop_id'] = $id;

        //查询是否存在该数据
        $info = $this->find(['shop_id'=>$data['shop_id']]);
        if($info['id']){ //存在则 修改
            $this->update($info['id'],$data);
        }else{ //不存在则 添加
            $this->store($data);
        }
    }
}