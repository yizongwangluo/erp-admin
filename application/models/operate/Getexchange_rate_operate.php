<?php

class Getexchange_rate_operate extends \Application\Component\Common\IData
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model ( 'data/admin_exchange_rate_data' );
    }

    /**
     * 获取汇率列表
     */
    public function getList(){

        $list = curl_get_https('https://www.mycurrency.net/US.json');
//        $list = file_get_contents('http://www.erp.com/rateceshi.json');
        $list = json_decode($list,true);

        $date = date("Y-m-d");
        $time = time();

        $data = [];
        if($list['rates']){

            foreach($list['rates'] as $value){
                $data[] = [
                    'id'=>$value['id'],
                    'name'=>$value['name'],
                    'name_zh'=>$value['name_zh'],
                    'code'=>$value['code'],
                    'currency_name'=>$value['currency_name'],
                    'currency_name_zh'=>$value['currency_name_zh'],
                    'currency_code'=>$value['currency_code'],
                    'rate'=>$value['rate'],
                    'code3'=>$value['code3'],
                    'date'=>$date,
                    'dateline'=>$time,
                ];
            }

            $ret = $this->admin_exchange_rate_data->addAll($data,true);

            log_message('get_rate_getList', $date.'|||'.$time.'|||'.$ret,true);

        }
    }
}