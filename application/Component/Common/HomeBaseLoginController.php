<?php
/**
 * 前台主页基础控制器
 * User: xiongbaoshan
 * Date: 2016/7/25
 * Time: 17:03
 */

namespace Application\Component\Common;


class HomeBaseLoginController extends \MY_Controller
{
   public  function __construct()
    {
        parent::__construct();
        $this->username();
    }

    //防不登录直接访问
    public function username(){
        $login=$this->session->userdata('login');
       if(isset($login)){
           //查询user表判断是否被禁用  是否已到期
           //禁用:2 跳出
           $time_C=time();
           $openid=$login["openid"];
           $user=model('wap/User_wap')->get_openid($openid);
           $this->cache->save ('user_due_date_redis', $user["due_date"] ,-1); //设置存储用户过期时间:永不过期
           if($user["status"] == 2 || $time_C>$user["due_date"]){
               //返回首页
               $this->load->helper('url');
               redirect('wap/Index/login');exit;
           }
       }else{
           //返回首页
           $this->load->helper('url');
           redirect('wap/Index/login');exit;

       }

    }

    /*
 * 获取省份列表缓存*/
    public function  get_province(){

        $list = $this->cache->get ('province_redis');
        return $list;

    }

    /*获取年份列表缓存
     * */

    public function get_year(){
        $list = $this->cache->get ('year_manager_redis');
        return $list;

    }

    /**
     * 获取科目要求列表缓存
     */
    public function get_demand_redis(){

        $list = $this->cache->get ('demand_redis');
        return $list;
    }

    /**
     * 获取默认信息缓存
     */
    public function get_default_redis(){

        $list = $this->cache->get ('default_mobile_redis');

        //为空时查询数据库
        if(!$list){
            $list =  model('data/default_mobile_data')->get_info(1);
            $this->cache->save ('default_mobile_redis' , $list , -1 );//设置永不过期
        }
        return $list;
    }


    /**
     * 获取学校列表缓存
     */
    public function get_schools_redis(){

        $list = $this->cache->get ('schools_redis');

        //为空时查询数据库
        if(!$list){
            $list =  model('data/schools_data')->get_field_by_where('id,logo,name,sort,participle,status',[],true);
            $this->cache->save ('schools_redis' , $list , -1 );//设置永不过期
        }
        return $list;
    }

}