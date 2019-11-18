<?php
/**
 * 后台redis基础控制器
 * User: liuxiaojie
 * Date: 2018/12/11
 * Time: 11:27
 */
namespace Application\Component\Common;

class AdminRedisCommonController extends AdminPermissionValidateController
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 缓存学校列表
     */
    public function set_schools_redis(){
        
        $list = model('data/schools_data')->get_field_by_where('id,logo,name,sort,participle,status',[],true);

        $this->cache->save ('schools_redis', $list ,-1); //设置永不过期
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

    /**
     * 缓存科目列表
     */
    public function set_subjects_redis(){
        $list = model('data/subjects_data')->get_field_by_where('*',[],true);
        $this->cache->save ('subjects_redis', $list ,-1); //设置永不过期
    }

    /**
     * 获取科目列表缓存
     */
    public function get_subjects_redis(){

        $list = $this->cache->get ('subjects_redis');

        //为空时查询数据库
        if(!$list){
            $list =  model('data/subjects_data')->get_field_by_where('*',[],true);
            $this->cache->save ('subjects_redis' , $list , -1 );//设置永不过期
        }
        return $list;
    }

    /**
     * 缓存科目要求列表
     */
    public function set_demand_redis(){
        $list = model('data/demand_data')->get_field_by_where('*',[],true);
        $this->cache->save ('demand_redis', $list ,-1); //设置永不过期
    }

    /**
     * 获取科目要求列表缓存
     */
    public function get_demand_redis(){

        $list = $this->cache->get ('demand_redis');

        //为空时查询数据库
        if(!$list){
            $list =  model('data/demand_data')->get_field_by_where('*',[],true);
            $this->cache->save ('demand_redis' , $list , -1 );//设置永不过期
        }
        return $list;
    }

    /**
     * 缓存省份列表
     */
    public function set_province_redis(){
        $list = model('data/province_data')->get_field_by_where('*',[],true);
        $this->cache->save ('province_redis', $list ,-1); //设置永不过期
    }

    /**
     * 获取省份列表缓存
     */
    public function get_province_redis(){

        $list = $this->cache->get ('province_redis');

        //为空时查询数据库
        if(!$list){
            $list =  model('data/province_data')->get_field_by_where('*',[],true);
            $this->cache->save ('province_redis' , $list , -1 );//设置永不过期
        }
        return $list;
    }

    /**
     * 缓存年份列表
     */
    public function set_year_manager_redis(){
        $list = model('data/year_manager_data')->get_field_by_where('id,year,status',[],true);
        $this->cache->save ('year_manager_redis', $list ,-1); //设置永不过期
    }

    /**
     * 获取年份列表缓存
     */
    public function get_year_manager_redis(){

        $list = $this->cache->get ('year_manager_redis');

        //为空时查询数据库
        if(!$list){
            $list =  model('data/year_manager_data')->get_field_by_where('id,year,status',[],true);
            $this->cache->save ('year_manager_redis' , $list , -1 );//设置永不过期
        }
        return $list;
    }

    /**
     * 缓存默认信息
     */
    public function set_default_redis(){
        $list = model('data/default_mobile_data')->get_info(1);
        $this->cache->save ('default_mobile_redis', $list ,-1); //设置永不过期
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
}