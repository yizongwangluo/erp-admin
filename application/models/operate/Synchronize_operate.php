<?php
/**
 * Created by PhpStorm.
 * User: akimbo
 * Date: 2020/2/11
 * Time: 19:02
 */

class Synchronize_operate extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
        set_time_limit ( 0 );
        $this->load->model ( 'orders/shopify_orders' );
        $this->load->model ( 'data/income_data' );
        $this->load->model ( 'operate/salary_operate' );
        $this->load->model ( 'operate/getoperate_operate' );
        $this->load->model ( 'data/order_synchro_data' );
        $this->load->model ( 'data/order_synchro_log_data' );
        $this->load->model ( 'data/order_data' );
        $this->load->model ( 'data/order_goods_data' );
        $this->load->model ( 'data/shop_data' );
    }

    public function sync($input)
    {
        $start_time = $input['start_time'];
        $end_time = $input['end_time'];
        $shop_id = $input['shop'];
        if(!$shop_id){
            $this->set_error('请选择需要同步的店铺！');
            return false;
        }
        if(!$start_time){
            $this->set_error('请输入开始时间！');
            return false;
        }
        if(!$end_time){
            $this->set_error('请输入结束时间！');
            return false;
        }
        $today = date("Y-m-d");
        if($start_time >= $today || $end_time >= $today){
            $this->set_error('开始时间/结束时间必须小于今天！');
            return false;
        }
        if($start_time != '' && $end_time != ''){
            //结束时间与开始时间的时间差
            $time_stamp_diff = strtotime($end_time) - strtotime($start_time);
            if($time_stamp_diff < 0){
                $this->set_error('开始时间必须小于结束时间！');
                return false;
            }
            if($time_stamp_diff > (30*24*60*60)){
                $this->set_error('最大可同步时间为31天！');
                return false;
            }
        }
        //获取时间段内日期
        $dates = $this->getDateFromRange($start_time,$end_time);
        //获取选择店铺信息
        $shop = $this->db->query ("select * from shop where id = $shop_id;" )->row_array ();
        if(!$shop){
            $this->set_error('同步失败：店铺不存在！');
            return false;
        }
        //清除时间段内数据
        $this->del_data($shop_id,$start_time,$end_time);
//        echo $start_time."～".$end_time."订单数据已清除\n";

        //同步订单
        $this->sync_save($shop,$dates);

        //更新每日运营数据
        foreach($dates as $k=>$time) {
            $this->getoperate_operate->get_data($shop_id,$time);
            if($this->db->affected_rows()<=0){ //添加失败
                $this->set_error('更新每日运营数据失败！');
                return false;
            }
        }

        //更新受影响的income表
        $today_ym = date("Y-m", time());
        $start_ym = date("Y-m", strtotime($start_time));
        if($start_ym < $today_ym){
            //清除该月已有数据
            $this->del_income($start_ym);
            //更新数据
            $start = date("Y-m-d", strtotime("+1 months", strtotime($start_time)));
            $this->income_data->timing_lists(strtotime($start));
        }
        $end_ym = date("Y-m", strtotime($end_time));
        if($end_ym < $today_ym){
            if($end_ym > $start_ym){
                //清除该月已有数据
                $this->del_income($end_ym);
                //更新数据
                $end = date("Y-m-d", strtotime("+1 months", strtotime($end_time)));
                $this->income_data->timing_lists(strtotime($end));
            }
        }

        //更新受影响的薪资表
        if($start_ym < $today_ym){
            //清除该月已有数据
            $this->del_salary($start_ym);
            //更新数据
            $start = date("Y-m-d", strtotime("+1 months", strtotime($start_time)));
            $this->salary_operate->set_salary_list(strtotime($start));
        }
        $end_ym = date("Y-m", strtotime($end_time));
        if($end_ym < $today_ym){
            if($end_ym > $start_ym){
                //清除该月已有数据
                $this->del_salary($end_ym);
                //更新数据
                $end = date("Y-m-d", strtotime("+1 months", strtotime($end_time)));
                $this->salary_operate->set_salary_list(strtotime($end));
            }
        }
        $this->set_error('同步成功！');

    }

    //清除时间段内数据
    public function del_data($shop_id,$start_time,$end_time)
    {
        //查出时间段内所有订单号
        $sql = "select group_concat(shopify_o_id) as shopify_o_ids from `order` where shop_id = $shop_id and datetime >= '$start_time' and datetime <= '$end_time'";
        $shopify_o_ids = $this->db->query($sql)->row_array ()['shopify_o_ids'];
        //若存在，删除时间段内已有订单商品表数据
        if($shopify_o_ids){
            $sql = "delete from `order_goods` where shopify_o_id in ($shopify_o_ids)";
            $query = $this->db->query($sql);
            if(!$query){
                $this->set_error('同步失败：清除订单商品表时间段内已有数据失败！');
                return false;
            }
        }


        //删除数据库内时间段已有订单数据
        $sql = "delete from `order` where shop_id = $shop_id and datetime >= '$start_time' and datetime <= '$end_time'";
        $query = $this->db->query($sql);
        if(!$query){
            $this->set_error('同步失败：清除订单表时间段内已有数据失败！');
            return false;
        }
    }

    //同步该店铺某时间段内的订单
    public function sync_save($shop, $dates)
    {
        foreach($dates as $k=>$time){
            $min_time = $time.'T00:00:00';
            $mix_time = $time.'T23:59:59';
            $url = 'https://'.$shop['shop_api_key'].':'.$shop['shop_api_pwd'].'@'.$shop['backstage'].'api/2020-01/orders.json?order=updated_at&updated_at_min='.$min_time.'&updated_at_max='.$mix_time.'&limit=250';
            $this->shopify_orders->get_order_page($shop,$url,$time,$min_time,$mix_time);
//            echo $time."订单数据已同步\n";
        }
    }

    //获取时间段内每一天的日期
    public function getDateFromRange($startdate, $enddate){

    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);

    // 计算日期段内有多少天
    $days = ($etimestamp-$stimestamp)/86400+1;

    // 保存每天日期
    $date = array();

    for($i=0; $i<$days; $i++){
        $date[] = date('Y-m-d', $stimestamp+(86400*$i));
    }

    return $date;
}

    //清除受影响的income表数据
    public function del_income($date)
    {
        //清除该月已有数据
        $sql ="delete from income where datetime = '$date'";
        $query = $this->db->query ($sql );
        if(!$query){
            $this->set_error('清除income表数据失败！');
        }
    }

    //清除受影响的salary表数据
    public function del_salary($date)
    {
        //清除该月已有数据
        $sql ="delete from salary where date = '$date'";
        $query = $this->db->query ($sql );
        if(!$query){
            $this->set_error('清除salary表数据失败！');
        }
    }
}

