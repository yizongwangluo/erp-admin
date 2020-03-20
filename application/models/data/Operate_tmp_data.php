<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/6
 * Time: 13:42
 */

class Operate_tmp_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/shop_data' );
        $this->load->model ( 'orders/shopify_orders' );
        $this->load->model ( 'operate/getoperate_operate' );
        $this->load->model ( 'operate/synchronize_operate' );

    }

    public function index( $admin_id ){
        if($admin_id == 1){
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain,
			d.user_name AS reviewer_name
		FROM
			operate_tmp a
		LEFT JOIN admin b ON a.user_id = b.id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN admin d ON a.reviewer = d.id
	) s";
        }
        else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.*, b.user_name,
			c.domain,
			d.user_name AS reviewer_name
		FROM
			operate_tmp a
		INNER JOIN (
			SELECT
				s_u_id,
				s_user_name AS user_name
			FROM
				admin_org_temp
			WHERE
				u_id = $admin_id
			GROUP BY
				s_u_id
		) b ON a.user_id = b.s_u_id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN admin d ON a.reviewer = d.id
	) s";
        }
        return $sql;
    }

    public function add( array $in )
    {
        $id = $in['id'];
        if ($in['ad_cost'] == '') {
            $this->set_error(' 请输入广告费用！');
            return false;
        }
        //根据id查出已有的运营数据
        $operate = $this->db->query ( "SELECT * FROM operate_tmp where id = $id" )->row_array ();
        //上传广告费后,计算相应数据
        $ROI = bcdiv($operate['turnover'],$in['ad_cost'],2);//ROI=营业额/广告费
        $ROI = empty($ROI) ? '0' : $ROI;
        //每单广告成本=广告费/付款订单数
        $unit_ad_cost = $in['ad_cost'] && $operate['paid_orders']?bcdiv($in['ad_cost'],$operate['paid_orders'],2):0;
        $unit_ad_cost = empty($unit_ad_cost) ? '0' : $unit_ad_cost;
        //产品总成本转换为美元
        $product_total_cost_usd = bcdiv($operate['product_total_cost'],$operate['exchange_rate'],2);
        $product_total_cost_usd = empty($product_total_cost_usd) ? '0' : $product_total_cost_usd;
        //毛利=营业额-广告费-手续费-产品总成本(美元)
        $gross_profit = $operate['turnover']-$in['ad_cost']-$operate['formalities_cost']-$product_total_cost_usd;
        //毛利(人民币)
        $gross_profit_rmb = bcmul($gross_profit,$operate['exchange_rate'],2);
        $gross_profit_rmb = empty($gross_profit_rmb) ? '0' : $gross_profit_rmb;
        //毛利率=毛利/营业额
        $gross_profit_rate = $gross_profit>0 && $operate['turnover']>0 ? bcdiv($gross_profit,$operate['turnover'],9):0;
        $gross_profit_rate = empty($gross_profit_rate) ? '0' : $gross_profit_rate;
        $in['review_status'] ? $in['review_status'] = $in['review_status'] : $in['review_status'] = 1;
        //写入数据库的数据
        $data = array(
            'ad_cost' => $in['ad_cost'],
            'review_status' => $in['review_status'],
            'review_time' => $in['review_time'],
            'reviewer' => $in['reviewer'],
            'ROI' => $ROI,
            'unit_ad_cost' => $unit_ad_cost,
            'gross_profit' => $gross_profit,
            'gross_profit_rmb' => $gross_profit_rmb,
            'gross_profit_rate' => $gross_profit_rate
        );

        function  filtrfunction($arr){
            if($arr === '' || $arr === null){
                return false;
            }
            return true;
        }

        if (!$id) {
            $data = array_filter($data,'filtrfunction');
            if (!$this->store($data)) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
        }else{
            unset($in['id']);
            if (!$this->operate_tmp_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

    public function get_domains( $admin_id )
    {
        if($admin_id == 1){
            $sql = "select id,domain,user_id from shop";
        }else{
            $sql = "SELECT a.id,a.domain,a.user_id FROM shop a INNER JOIN (SELECT s_u_id FROM admin_org_temp WHERE u_id = $admin_id GROUP BY s_u_id) b ON a.user_id = b.s_u_id";
        }
        $domains = $this->db->query ( $sql )->result_array ();
        return $domains;
    }

    public function get_users($admin_id)
    {
        if($admin_id == 1){
            $sql = "select id as s_u_id,real_name as s_real_name,user_name as s_user_name from admin order by s_u_id desc";
        }else{
            $sql = 'select s_u_id,s_real_name,s_user_name from admin_org_temp where u_id = '.$admin_id.' group by s_u_id order by s_u_id desc';
        }
        $users = $this->db->query ( $sql )->result_array ();
        return $users;
    }
    
    public function get_sum( $sql, $condition )
    {
        $sql_sum = $sql.$condition[0];
        $data = $this->db->query ( $sql_sum )->result_array ();
        $sum = [];
        foreach($data as $v){
            $sum['turnover'] += $v['turnover'];
            $sum['paid_orders'] += $v['paid_orders'];
            $sum['ad_cost'] += $v['ad_cost'];
            $sum['formalities_cost'] += $v['formalities_cost'];
            $sum['product_total_cost'] += $v['product_total_cost'];
            $sum['gross_profit'] += $v['gross_profit'];
            $sum['gross_profit_rmb'] += $v['gross_profit_rmb'];
        }
        //总营业额
        $sum['turnover'] = floor($sum['turnover'] * 100) / 100;
        //总广告费
        $sum['ad_cost'] = floor($sum['ad_cost'] * 100) / 100;
        //总手续费
        $sum['formalities_cost'] = floor($sum['formalities_cost'] * 100) / 100;
        //总产品总成本
        $sum['product_total_cost'] = floor($sum['product_total_cost'] * 100) / 100;
        //总毛利（$）
        $sum['gross_profit'] = floor($sum['gross_profit'] * 100) / 100;
        //总毛利（￥）
        $sum['gross_profit_rmb'] = floor($sum['gross_profit_rmb'] * 100) / 100;
        //总毛利率 = 总毛利/总营业额
        $sum['gross_profit_rate'] = $sum['gross_profit'] && $sum['turnover']?bcdiv($sum['gross_profit'],$sum['turnover'],9):0;
        $sum['gross_profit_rate'] = empty($sum['gross_profit_rate']) ? '0.000000000' : $sum['gross_profit_rate'];
        //总ROI = 总营业额/总广告费
        $sum['ROI'] = $sum['turnover']?bcdiv($sum['turnover'],$sum['ad_cost'],2):0;
        $sum['ROI'] = empty($sum['ROI']) ? '0.00' : $sum['ROI'];
        return $sum;

}

    public function get_product_list( $id )
    {
        //根据id查出已有的运营数据
        $operate = $this->db->query ( "SELECT * FROM operate_tmp WHERE id = $id" )->row_array ();
        $shop_id = $operate['shop_id'];
        $date = $operate['datetime'];
        //获取该店铺该天的付款订单id (状态为已支付)
        $sql = "SELECT
                b.sku_id,
                sum(b.quantity) AS quantity
            FROM
                `order` a INNER JOIN
                order_goods b
            on
            a.shopify_o_id = b.shopify_o_id WHERE  a.shop_id = $shop_id AND a.datetime = '$date' AND a.financial_status = 'paid' GROUP BY b.sku_id";
        $sku_ids = $this->db->query ( $sql )->result_array ();
        $sku_ids = array_column($sku_ids,null,'sku_id'); //把数组中sku_id变成key值

        foreach($sku_ids as $k=>$v){ //拆分捆绑sku
            $sku_tmp = explode('+',$v['sku_id']);
            foreach($sku_tmp as $i=>$t){
                $sku_id_tmp = explode('*',$v['sku_id']);
                if(count($sku_id_tmp)>1){
                    if(isset($sku_ids[$sku_id_tmp[0]])){ //存在该key
                        $sku_ids[$sku_id_tmp[0]]['quantity'] +=  $sku_id_tmp[1]*$v['quantity'];
                    }else{ //不存在
                        $sku_ids[$sku_id_tmp[0]] = $sku_id_tmp[1]*$v['quantity'];
                    }
                    if(count($sku_id_tmp)>1){
                        unset($sku_ids[$k]);
                    }
                }
            }
        }

        $sku_ids_str = implode("','",array_column($sku_ids,'sku_id'));
        $sku_ids_str_re = implode("|",array_column($sku_ids,'sku_id'));

        if(!empty($sku_ids_str)){
            //获取该店铺该天已付订单的所有商品(商品名称,sku编码,出售总数,产品重量,产品价格)
            $sql = "select b.code,b.alias,a.`name`,b.price,b.weight from goods a LEFT JOIN goods_sku b on a.id=b.spu_id	where  b.code in ('".$sku_ids_str."') or b.alias REGEXP '(^|,)(".$sku_ids_str_re.")(,|$)' GROUP BY b.code";
            $data = $this->db->query ( $sql )->result_array ();

            //获取产品成本明细
            foreach($data as $k => $v){
                $data[$k]['id'] = $k+1;
                $data[$k]['freight'] = (int)$operate['freight'];

                $alias_code = explode(',',$v['alias']);
                $alias_code[] = $v['code'];

                foreach($alias_code as $value){
                    if(isset($sku_ids[$value])){
                        $data[$k]['quantity'] = $sku_ids[$value]['quantity'];
                        $data[$k]['product_cost'] = bcadd(($sku_ids[$value]['quantity']*$v['price']),($sku_ids[$value]['quantity']*$v['weight']*$operate['freight']),2);
                        break; // 终止循环
                    }
                }
            }
        }else{
            $data = array();
        }

        return $data;
    }

    //动态获取今天和昨天的运营数据
    public function get_moving()
    {
        $today = date("Y-m-d");
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $mv['today'] = $this->get_operate_data($today);
        $mv['yesd'] = $this->get_operate_data($yesterday);
    }

    //动态获取某天的运营数据
    public function get_operate_data($time)
    {
        $shop_list = $this->shop_data->lists();

        //没有店铺时 跳出程序
        if(empty($shop_list)){
            return false;
        }

        $min_time = $time.'T00:00:00';
        $mix_time = $time.'T23:59:59';

        foreach($shop_list as $k=>$value){
            $url = 'https://'.$value['shop_api_key'].':'.$value['shop_api_pwd'].'@'.$value['backstage'].'api/2020-01/orders.json?order=updated_at&updated_at_min='.$min_time.'&updated_at_max='.$mix_time.'&limit=250';
//            echo $url;
            $this->get_order_page($value,$url,$time,$min_time,$mix_time);
        }
    }

    public function get_order_page($arr = [],$url = '',$time = '',$min_time = '',$mix_time = '',$page = 1)
    {
        $shop_id = $arr['id'];
        $order_json = $this->curl_get_https($url);
        $order_list = json_decode($order_json,true);
        $order_cout = count($order_list['orders']);

        if($order_list){
            if($order_cout>0){ //有订单时
                foreach($order_list as $v){
                    $j = 1;
                    foreach($v as $k=>$val){
                        $date = substr($val['updated_at'],0,strpos($val['updated_at'], 'T'));
                        if($val['financial_status'] == 'paid' && $date == $time){
                            $orders[$j]['shopify_o_id'] = $val['id'];
                            $orders[$j]['total_price_usd'] = $val['total_price_usd'];
                            $orders[$j]['total_weight'] = $val['total_weight'];
                        }
//                        $a = 1;
//                        foreach($val['line_items'] as $i=>$item){
//                            $orders[$j]['item'][$a]['product_id'] = $item['product_id'];
//                            $orders[$j]['item'][$a]['sku_id'] = $item['sku'];
//                            $orders[$j]['item'][$a]['quantity'] = $item['quantity'];
//                            $orders[$j]['item'][$a]['shopify_o_id'] = $val['id'];
//                            $a++;
//                        }
                        $j++;
                    }
                }
            }
            $next_link = $this->shopify_orders->get_header($url,$arr['shop_api_key'],$arr['shop_api_pwd']); //下页链接

            if($next_link){
                $page++;
                $this->get_order_page($arr,$next_link,$time,$min_time,$mix_time,$page);
            }
        }
    }

    function curl_get_https($url){
//	return $url;
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;
    }
}


