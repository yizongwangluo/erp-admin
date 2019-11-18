<?php
/**
 * 第一个首充活动（活动地址：http://888.265g.com/hd/first_recharge）
 * User: xiongbaoshan
 * Date: 2016/9/23
 * Time: 14:58
 */

namespace Application\Component\Concrete\DiscountActivity\Rule;


use Application\Component\Contract\DiscountActivity\Rule;
use Application\Component\Traits\ErrorReporter;

class Hd_first_recharge extends \MY_Model implements Rule
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

        //必需是首充
        if($goods['type']!='first_recharge'){
            return 0;
        }

        //满XX元=》减XX元
        $config=[
            26=>5,
            100=>20,
            265=>60,
            888=>180
        ];

        $step=find_step(array_keys($config),$order_amount);
        if(!$step){
            return 0;
        }

        return (float)$config[$step];
    }

}