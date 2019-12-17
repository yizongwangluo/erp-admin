<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:45
 */

class Royalty_px_data extends \Application\Component\Common\IData{

    /**
     * 添加
     * @param int $id
     * @param string $input
     * @return bool
     */
    public function add($id = 0,$input = ''){

        $input = json_decode($input,true);

        if(count($input) && $id){

            $sql = 'INSERT INTO royalty_px (r_id,range_start,range_end,ratio,remarks)  VALUES ';

            foreach($input as $v){
                $sql .= '('.$id.','.$v['range_start'].','.$v['range_end'].','.$v['ratio'].',"'.$v['remarks'].'"),';
            }
            $sql = rtrim($sql,',');

            $query = $this->db->query($sql);
            if($this->db->affected_rows()<=0){
                $this->set_error('添加 提成系数px 失败');return false;
            }

            return true;
        }
        return false;
    }

    /**
     * 添加
     * @param array $input
     * @return bool|int
     */
    public function add_one($input = [])
    {
        if(!$input['range_end'] || !$input['ratio']){
            $this->set_error('参数未填写完整');return false;
        }
        return $this->store($input);
    }

    /**
     * 修改
     * @param array $input
     * @return bool|int
     */
    public function edit($input = [])
    {
        if(!$input['range_end'] || !$input['ratio']){
            $this->set_error('参数未填写完整');return false;
        }

        $id = $input['id'];
        unset($input['id']);
        return $this->update($id,$input);
    }

}