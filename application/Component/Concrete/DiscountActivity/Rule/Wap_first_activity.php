<?php
/**
 * M端限时特惠活动
 * User: xiongbaoshan
 * Date: 2016/9/23
 * Time: 16:01
 */

namespace Application\Component\Concrete\DiscountActivity\Rule;


use Application\Component\Contract\DiscountActivity\Rule;
use Application\Component\Traits\ErrorReporter;

class Wap_first_activity extends \MY_Model implements Rule
{
    use ErrorReporter;

    function __construct()
    {
        parent::__construct();
        $this->load->model('data/goods_data');

    }

    public function discount($user_id, $goods_id, $buy_quantity, $order_amount)
    {
        $goods=$this->goods_data->get_info($goods_id);

        //必需M端
        if(!IS_WAP){
            return 0;
        }

        //必需规定商品
        if(!in_array(5,explode(',',$goods['pos_flag']))){
            return 0;
        }

        //少于10元一律0.1元
        return $order_amount-0.1;
    }

}