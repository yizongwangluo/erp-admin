<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/20
 * Time: 9:59
 */

class Getoperate_tmp_operate extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
        set_time_limit(0);//设定超时时间
        $this->load->model ( 'data/operate_tmp_data' );
        $this->load->model ( 'data/shop_data' );
        $this->load->model ( 'data/admin_data' );
        $this->load->model ( 'data/royalty_rules_data' );
        $this->load->model ( 'operate/getoperate_operate' );
    }

    /**
     * 根据店铺获取指定时间的运营数据
     * @return bool
     */
    public function get_datas_tmp()
    {
        //获取当前所有店铺
        $shops = $this->shop_data->get_field_by_where(['id','user_id'],['status'=>1],true);

        //没有店铺时 跳出程序
        if(empty($shops)){  return false;  }

        $time = date('Y-m-d H:i:s'); //当前时间
//        $data_arr = [date("Y-m-d",strtotime("-1 day")),date("Y-m-d")];//获取 昨天/今天 的日期

        if(date('i')<30){
            $data_arr = [date("Y-m-d",strtotime("-1 day"))];//获取 昨天 的日期
        }else{
            $data_arr = [date("Y-m-d")];//获取 今天 的日期
        }


        foreach($data_arr as $item){//循环日期

            foreach ($shops as $v){//循环店铺

                $data = [];//初始化数组
                $data['datetime'] = $item;
                $data['insert_time'] = $time; //添加数据时间
                $data['shop_id'] = $v['id'];//店铺id
                $data['user_id'] = $v['user_id'];//用户ID

                $data = $this->getoperate_operate->common_operate($data);

                if($data){
                    //将数据存入数据库
                    $this->operate_tmp_data->store($data,true);
                }
            }
        }
    }
}

