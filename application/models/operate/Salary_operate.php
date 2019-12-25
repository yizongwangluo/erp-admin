<?php

class Salary_operate extends \Application\Component\Common\IData
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 生成薪资列表
     * @param string $datetime
     * @return bool
     */
    public function set_salary_list($datetime = ''){

        $datetime = $datetime?$datetime:time(); //时间戳
        $datetime = date('Y-m',strtotime('-1 month',$datetime)); //上个月日期 格式2019-12

        $sql = 'select b.u_id,b.money from admin a LEFT JOIN income b on a.id=b.u_id where a.is_disable=0 and b.datetime="'.$datetime.'"';
        $query = $this->db->query($sql);
        $list = $query->result_array(); //查出薪资列表

        if(count($list)){ //列表不为空时

            $sql = 'INSERT into salary(user_id,commission,date) VALUES ';
            $sql_val = [];
            foreach($list as $value){
                $sql_val[] = '('.$value['u_id'].','.$value['money'].',"'.$datetime.'")';
            }
            $sql.=implode(',',$sql_val);
            $query = $this->db->query($sql);
            if($this->db->affected_rows()<=0){
                log_message('set_salary_list','sql = '.$sql,true);
                return false;
            }
            return true;
        }
    }
}