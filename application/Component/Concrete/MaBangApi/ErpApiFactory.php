<?php
/**
 * 通途对接api抽象类
 * User: liuxiaojie
 * Date: 2019/12/06
 * Time: 09:24
 */

namespace Application\Component\Concrete\MaBangApi;

class ErpApiFactory
{
    private $url;
    private $developerId;
    private $key;

    public function __construct()
    {
        $this->url = 'http://openapi.mabangerp.com';
//        $this->developerId = '100221'; //账号
//        $this->key = '97fcf2b0a4466dc4d0ae53d005865266'; //秘钥
//
        //正式
        $this->developerId = '100412'; //账号
        $this->key = 'c31d615cf940be068ceff260b4a3e27e'; //秘钥
    }

    /**
     * 获取订单列表
     * @param array $data_arr
     * @return mixed|string
     */
    public function get_lists($data_arr = [])
    {

        $time = time();//当前时间戳
        $date = date("Y-m-d", $time - (60 * 60 * 24)); //昨日日期

        $data = [];
        $data['developerId'] = $this->developerId;
        $data['timestamp'] = $time;
        $data['action'] = 'get-order-list';

        $data = array_merge($data,$data_arr);

        $ret = $this->http_url($this->url, $data, $this->get_sign($data));

        //记录日志
        log_message ( 'erp_get_order_mabang', json_encode($data).'------'.$ret, true );
        //记录日志end

        $ret = json_decode($ret,true);

        return $ret;

    }

    /**
     * 新增商品
     * @param array $sku_data
     * @return mixed|string
     */
    public function add_stock($sku_data = [])
    {

        $time = time();//当前时间戳

        $data = [];
        $data['developerId'] = $this->developerId;
        $data['timestamp'] = $time;
        $data['action'] = 'do-add-stock';

        $data['stockSku'] = $sku_data['code'];
        $data['nameCN'] = $sku_data['name'].'-'.$sku_data['norms'].$sku_data['norms1'];
        $data['nameEN'] = $sku_data['name_en']; //英文名

        $data['status'] = 3;

        $data['picture'] = $sku_data['img']; //图片

        $size = explode('*',$sku_data['size']);

        $data['length'] = $size[0]; //长
        $data['width'] = $size[1]; //宽
        $data['height'] = $size[2]; //高
        $data['weight'] = $sku_data['weight']; //重量
        $data['declareEname'] = $sku_data['dc_name_en']; //英文报关名
        $data['declareName'] = $sku_data['dc_name']; //中文报关名
        $data['noLiquidCosmetic'] = $sku_data['is_liquid']; //液体
        $data['purchasePrice'] = $sku_data['price']; //最新采购价
//        $data['declareValue'] = $sku_data['price']; //	申报价值
//        $data['salePrice'] = $sku_data['price']; //售价
        $data['defaultCost'] = $sku_data['price']; //统一成本价
        $data['hasBattery'] = $sku_data['is_battery']?1:2; //	带电池 1.是 2.否
        $data['isTort'] = $sku_data['is_tort']?1:2; //		侵权 1.是 2.否
        $data['magnetic'] = $sku_data['is_magnetism']?1:2; //		带磁 1.是 2.否
        $data['powder'] = $sku_data['is_powder']?1:2; //		粉末 1.是 2.否
        $data['remark'] = $sku_data['remarks']; //备注
        $data['autoCreateSupplier'] = '1'; //备注
//
        $data['suppliersData'] = $this->json_encode_data([['name'=>$sku_data['supplier_name'],'productLinkAddress'=>$sku_data['source_address']]]); //关联供应商信息
        $data['warehouseData'] = $this->json_encode_data([['name'=>$sku_data['warehouse_name']]]); //仓库

//        $data['virtualSkus'] = $sku_data['alias']; //别名
        $data['virtualSkus'] = ''; //别名

        $ret = $this->http_url($this->url, $data, $this->get_sign($data));

        //记录日志
        log_message ( 'mabang_add_stock', json_encode($data).'------'.$ret, true );
        //记录日志end

        $ret = json_decode($ret,true);

        return $ret;

    }

    /**
     * 修改商品
     * @param array $sku_data
     * @return mixed|string
     */
    public function change_stock($sku_data = [])
    {
        $time = time();//当前时间戳

        $data = [];
        $data['developerId'] = $this->developerId;
        $data['timestamp'] = $time;
        $data['action'] = 'do-change-stock';


        $data['stockSku'] = $sku_data['code'];
        $data['nameCN'] = $sku_data['name'].'-'.$sku_data['norms'].$sku_data['norms1'];
        $data['nameEN'] = $sku_data['name_en']; //英文名

        $data['status'] = 3;

        $data['picture'] = $sku_data['img']; //图片

        $size = explode('*',$sku_data['size']);

        $data['length'] = $size[0]; //长
        $data['width'] = $size[1]; //宽
        $data['height'] = $size[2]; //高
        $data['weight'] = $sku_data['weight']; //重量
//        $data['parentCategoryName'] = $sku_data['className'];
        $data['declareEname'] = $sku_data['dc_name_en']; //英文报关名
        $data['declareName'] = $sku_data['dc_name']; //中文报关名
        $data['noLiquidCosmetic'] = $sku_data['is_liquid']; //液体
        $data['purchasePrice'] = $sku_data['price']; //最新采购价
        $data['hasBattery'] = $sku_data['is_battery']?'1':'2'; //	带电池 1.是 2.否
        $data['isTort'] = $sku_data['is_tort']?'1':'2'; //		侵权 1.是 2.否
        $data['magnetic'] = $sku_data['is_magnetism']?'1':'2'; //		带磁 1.是 2.否
        $data['powder'] = $sku_data['is_powder']?'1':'2'; //		粉末 1.是 2.否
        $data['remark'] = $sku_data['remarks']; //备注

        $data['suppliersData'] = $this->json_encode_data([['name'=>$sku_data['supplier_name'],'productLinkAddress'=>$sku_data['source_address']]]); //关联供应商信息
        $data['warehouseData'] = $this->json_encode_data([['name'=>$sku_data['warehouse_name']]]); //仓库

//        $data['virtualSkus'] = $sku_data['alias']; //别名
        $data['virtualSkus'] = ''; //别名

        $ret = $this->http_url($this->url, $data, $this->get_sign($data));

        //记录日志
        log_message ( 'mabang_change_stock', json_encode($data).'------'.$ret, true );
        //记录日志end

        $ret = json_decode($ret,true);

        return $ret;

    }


    /**
     * json
     * @param array $data
     * @return string
     */
    public function json_encode_data($data = []){
        //JSON_UNESCAPED_UNICODE（中文不转为unicode ，对应的数字 256）
        //JSON_UNESCAPED_SLASHES （不转义反斜杠，对应的数字 64）
        //JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES = 320
        return json_encode($data,'320');
    }


    /**
     * 生成秘钥
     * @param array $data
     * @return string
     */
    public function get_sign($data = [])
    {
//        $content = json_encode($data, JSON_UNESCAPED_UNICODE);
        $content = $this->json_encode_data($data);
        $hash = hash_hmac('sha256', $content, $this->key);
        return $hash;

    }

    /**
     * http请求
     * @param string $url
     * @param string $data
     * @param string $sign
     * @return mixed|string
     */
    public function http_url($url = '', $data = '', $sign = '')
    {
        $ch = curl_init();
        $header = array('Content-Type: application/json; ', 'Authorization:'.$sign);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXY, '162.14.17.156:80');
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post设置头
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // post的变量
        $arr = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        //打印获得的数据
        return $result;
    }

}