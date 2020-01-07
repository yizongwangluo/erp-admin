<?php
/**
 * Created by PhpStorm.
 * User: liuxiaojie
 * Date: 2019/11/19
 * Time: 9:59
 */

class Income_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
    }

    /**
     * 获取业绩列表
     * @param int $u_id
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function get_lists($u_id = 0,$where = [],$page = 1,$limit = 10){

        $total = 0;
        $info = [];

        //admin账号时
        if($u_id==1){
            $sql_total  = 'select count(*) as total from admin';
            $query = $this->db->query($sql_total);
            $total = $query->result_array()[0]['total'];
            if($total){
                $sql  = 'select id as u_id,user_name,org_id from admin';
                $sql .=  ' limit '.($page-1)*$limit.','.$limit;
                $query = $this->db->query($sql);
                $info = $query->result_array();
            }
        }else{
            $sql_total = 'select COUNT(DISTINCT s_u_id) as total from admin_org_temp where u_id='.$u_id;
            $query = $this->db->query($sql_total);
            $total = $query->result_array()[0]['total'];
            if($total){
                $sql = 'select s_u_id as u_id,s_user_name as user_name,group_concat(s_o_id) as org_id from admin_org_temp where u_id='.$u_id.' GROUP BY s_u_id';
                $sql .=  ' limit '.($page-1)*$limit.','.$limit;
                $query = $this->db->query($sql);
                $info = $query->result_array();
            }
        }

        $date = explode('-',$where['datetime']);
        $year = $date[0];
        $month = $date[1];

        foreach($info as $k=>$v){ //循环计算个人业绩
            if(strpos($v['org_id'],',')===false){ //单个职位（组员）

                $info[$k] = array_merge($info[$k],$this->get_personal_royalty($v['u_id'],$v['org_id'],$year,$month)); //计算个人提成

            }else{ //多个职位（组长）

                //查看提成规则
                $sql = 'select * from royalty_rules where o_id in ('.$v['org_id'].')';
                $royalty_rules = $this->db->query($sql)->result_array();
                if(count($royalty_rules) !=2){
                    $info[$k]['remarks'] = '未找到相应提成规则，请检查该员工信息';
                }else{
                    $data = [];
                    $royalty_rules = array_column($royalty_rules,null,'type');

                    //个人业绩
                    $data[1] = $this->get_personal_royalty($v['u_id'],$royalty_rules[1]['o_id'],$year,$month);

                    //管理提成
                    $data[2] = $this->get_team_royalty($v['u_id'],$royalty_rules[2]['o_id'],$royalty_rules[1]['o_id'],$year,$month);

                    foreach($data[1] as $i=>$t){
                        if($i=='remarks'){
                            $info[$k][$i] = $data[1][$i].','.$data[2][$i];
                        }else{
                            $info[$k][$i] = $data[1][$i]+$data[2][$i];
                            if($i=='gross_profit_rate'){
                                $info[$k][$i] = $info[$k][$i]/2;
                            }
                        }
                    }
                }
            }
        }

        return array(
            'page_count' => $this->page->get_page_count(),
            'page_num' => $page,
            'page_size' => $limit,
            'total' => $total,
            'data' => $info
        );
    }

    /**
     * 生成列表
     * @param string $datetime
     * @return bool
     */
    public function timing_lists($datetime = ''){

        $user_list = [];

        $datetime = $datetime?$datetime:time(); //时间戳
        $datetime = date('Y-m',strtotime('-1 month',$datetime)); //上个月日期 格式2019-12
        $date = explode('-',$datetime);
        $year = $date[0];
        $month = $date[1];

        $query = $this->db->query('select id as u_id,user_name,org_id from admin where is_disable=0'); //查看未被禁用的账号列表
        $user_list = $query->result_array();

        if(count($user_list)){
            foreach($user_list as $k=>$v){ //循环计算个人业绩

                //设置初始默认数据
                $user_list[$k]['turnover'] = 0;
                $user_list[$k]['paid_orders'] = 0;
                $user_list[$k]['ad_cost'] = 0;
                $user_list[$k]['product_total_cost'] = 0;
                $user_list[$k]['gross_profit_rmb'] = 0;
                $user_list[$k]['gross_profit_rate'] = 0;
                $user_list[$k]['money'] = 0;
                $user_list[$k]['remarks'] ='';
                //设置初始默认数据end

                if(strpos($v['org_id'],',')===false){ //单个职位（组员）

                    $user_list[$k] = array_merge($user_list[$k],$this->get_personal_royalty($v['u_id'],$v['org_id'],$year,$month)); //计算个人提成

                }else{ //多个职位（组长）

                    //查看提成规则
                    $sql = 'select * from royalty_rules where o_id in ('.$v['org_id'].')';
                    $royalty_rules = $this->db->query($sql)->result_array();
                    if(count($royalty_rules) !=2){
                        $user_list[$k]['remarks'] = '未找到相应提成规则，请检查该员工信息';
                    }else{
                        $data = [];
                        $royalty_rules = array_column($royalty_rules,null,'type');

                        //个人业绩
                        $data[1] = $this->get_personal_royalty($v['u_id'],$royalty_rules[1]['o_id'],$year,$month);

                        //管理提成
                        $data[2] = $this->get_team_royalty($v['u_id'],$royalty_rules[2]['o_id'],$royalty_rules[1]['o_id'],$year,$month);

                        foreach($data[1] as $i=>$t){
                            if($i=='remarks'){
                                $user_list[$k][$i] = $data[1][$i].','.$data[2][$i];
                            }else{
                                $user_list[$k][$i] = $data[1][$i]+$data[2][$i];
                                if($i=='gross_profit_rate'){
                                    $user_list[$k][$i] = $user_list[$k][$i]/2;
                                }
                            }
                        }
                    }
                }
            }

            $sql = 'INSERT into income(u_id,turnover,ad_cost,product_total_cost,paid_orders,gross_profit_rate,gross_profit_rmb,datetime,money,remarks)  VALUES ';
            $salary_sql = 'INSERT into salary(user_id,commission,`date`)  VALUES ';
            $sql_val = [];
            $salary_sql_val = [];
            foreach($user_list as $value){
                $sql_val[] = '('.$value['u_id'].','.$value['turnover'].','.$value['ad_cost'].','.$value['product_total_cost'].','.$value['paid_orders'].','.$value['gross_profit_rate'].','.$value['gross_profit_rmb'].',"'.$datetime.'",'.$value['money'].',"'.$value['remarks'].'")';
                $salary_sql_val[] = '('.$value['u_id'].','.$value['money'].',"'.$datetime.'")';
            }

            $sql .= implode(',',$sql_val);
            $query = $this->db->query($sql);

            $salary_sql .= implode(',',$salary_sql_val);
            $query = $this->db->query($salary_sql);

            /*if($this->db->affected_rows()<=0){
                log_message('timing_lists','sql = '.$sql,true);
                $this->set_error('添加失败');return false;
            }*/
            return true;
        }
    }

    /**
     * 个人提成计算
     * @param int $u_id
     * @param int $org_id
     * @param int $year
     * @param int $month
     * @return array
     */
    public function get_personal_royalty($u_id = 0,$org_id = 0,$year = 0,$month = 0){

        $data = [];

        if(!$u_id || !$org_id || !$year || !$month){  return $data;  }

        //获取本月的营业额$、订单数、广告费$、产品总成本￥、毛利（rmb）、毛利率
        $sql = 'select SUM(turnover) as turnover,sum(paid_orders) as paid_orders,SUM(ad_cost) as ad_cost,SUM(product_total_cost) as product_total_cost,sum(gross_profit_rmb) as gross_profit_rmb,(sum(gross_profit_rate)/count(*)) as  gross_profit_rate from operate where user_id='.$u_id.' and year(datetime)="'.$year.'" and month(datetime)="'.$month.'"';
        $sum_money = $this->db->query($sql)->row_array();
        if($sum_money){
            foreach($sum_money as $k=>$value){
                $sum_money[$k] = $value?$value:0;
            }
            $data = array_merge($data,$sum_money);
        }

        //查看提成规则
        $sql = 'select id from royalty_rules where o_id='.$org_id;
        $royalty_rules = $this->db->query($sql)->row_array();

        if($royalty_rules){ //有提成规则时

            $px = $sum_money['gross_profit_rmb']; //毛利 人民币
            $gpm = $sum_money['gross_profit_rate']; //毛利率

            $get_royalty = $this->get_royalty($royalty_rules['id'],$px,$gpm); //计算提成
            $data['money'] = $get_royalty['money'];
            $data['remarks'] = $get_royalty['remarks'];

        }else{
            $data['remarks'] = '未找到相应提成规则，请检查该员工信息';
        }
        return $data;
    }

    /**
     * 组长提成计算
     * @param int $u_id
     * @param int $o_id 部门id
     * @param int $s_o_id 管理的部门id
     * @param int $year
     * @param int $month
     * @return array
     */
    public function get_team_royalty($u_id = 0,$o_id = 0,$s_o_id = 0,$year = 0,$month = 0){

        $data = [];

        if(!$u_id || !$o_id || !$s_o_id || !$year || !$month){ return $data;    } //信息不完整，直接返回

        //获取部门所有人 id
        $sql = 'select id from admin where FIND_IN_SET("'.$s_o_id.'",org_id) ';
        $team_ids = $this->db->query($sql)->result_array();
        if(count($team_ids)>1){ //部门不止自己一个人时
            $team_ids = array_column($team_ids,'id','id');
            unset($team_ids[$u_id]); //过滤自己的id
            $team_ids = implode(',',$team_ids);

            //获取本月部门的营业额$、订单数、广告费$、产品总成本￥、毛利（rmb）、毛利率
            $sql = 'select SUM(turnover) as turnover,sum(paid_orders) as paid_orders,SUM(ad_cost) as ad_cost,SUM(product_total_cost) as product_total_cost,sum(gross_profit_rmb) as gross_profit_rmb,(sum(gross_profit_rate)/count(*)) as  gross_profit_rate from operate where user_id in ('.$team_ids.') and year(datetime)="'.$year.'" and month(datetime)="'.$month.'"';
            $sum_money = $this->db->query($sql)->row_array();
            if($sum_money){
                $data = array_merge($data,$sum_money);
            }

            $px = $sum_money['gross_profit_rmb']; //毛利 人民币
            $gpm = $sum_money['gross_profit_rate']; //毛利率

            //查看提成规则
            $sql = 'select id from royalty_rules where o_id='.$o_id;
            $royalty_rules = $this->db->query($sql)->row_array();
            if($royalty_rules){ //有提成规则时
                $get_royalty = $this->get_royalty($royalty_rules['id'],$px,$gpm);
                $data['money'] = $get_royalty['money'];
                $data['remarks'] = $get_royalty['remarks'];
            }
        }

        return $data;
    }



    /**
     * 计算提成
     * @param int $r_id
     * @param int $px
     * @param int $gpm
     * @return int
     */
    public function get_royalty($r_id = 0,$px = 0,$gpm = 0){
        if($r_id && $px && $gpm){
            $royalty_px = $this->db->query('select * from royalty_px where r_id='.$r_id)->result_array();
            $royalty_gpm = $this->db->query('select * from royalty_gpm where r_id='.$r_id)->result_array();

            if($royalty_px && $royalty_gpm){

                $px_c = $this->get_coefficient($px,$royalty_px);
                $gpm_c = $this->get_coefficient($gpm,$royalty_gpm);

                $money = bcmul($px*$px_c/100,$gpm_c,'2');

                return ['money'=>$money,'remarks'=>''];

            }
            return ['money'=>0,'remarks'=>'该部门提成规则未设置'];
        }
        return ['money'=>0,'remarks'=>''];
    }

    /**
     * 获得系数
     * @param int $a
     * @param array $arr
     * @return mixed
     */
    public function get_coefficient($a = 0,$arr = []){
        foreach($arr as $v){
            if($a>=$v['range_start'] && $a<$v['range_end']){
                return $v['ratio'];
            }
        }
        return false;
    }
}