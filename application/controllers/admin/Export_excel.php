<?php

/**
 * 商品列表
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Export_excel extends \Application\Component\Common\AdminPermissionValidateController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('data/goods_data');
        $this->load->model('data/goods_sku_data');
    }


    //导出
    public function daochu(){

        $ids = input('ids');
        $ids = trim($ids,',');

        //获取商品详情
        $spu_list = $this->goods_data->get_list_inids($ids);

        foreach($spu_list as $k=>$v){
            $spu_list[$k]['sku_list'] = $this->goods_sku_data->get_list_spuid($v['id']);
        }

        header("Content-type:application/vnd.ms-excel");   //声明内容类型为excel
        header("Content-Disposition:attachment;filename=excel导出.xls");  //content-disposition设置attachment为弹窗下载，inline时会内嵌浏览器显示，当然对jpg等文件有效，excel文件不能内嵌，可自行翻阅文档了解；filename定义文件名称与扩展名
        echo "SKU\t";
        echo "产品名称\t";
        echo "SKU别名\t";
        echo "属性名1\t";
        echo "属性值1\t";
        echo "属性名2\t";
        echo "属性值2\t";
        echo "SKU属性编号\t";
        echo "产品重量(g)\t";
        echo "采购单价\t";
        echo "SKU属性别名\t";
        echo "仓库名称1\t";
        echo "库存数量1\t";
        echo "货位1\t";
        echo "仓库名称2\t";
        echo "库存数量2\t";
        echo "货位2\t";
        echo "产品体积(长*宽*高)CM\t";
        echo "产品特点\t";
        echo "备注\t";
        echo "供应商名称\t";
        echo "最小采购量(MOQ)\t";
        echo "采购链接\t";
        echo "分类\t";
        echo "品牌\t";
        echo "特性标签\t";
        echo "中文配货名称\t";
        echo "英文配货名称\t";
        echo "中文报关名\t";
        echo "英文报关名\t";
        echo "包装材料名称\t";
        echo "包装成本(CNY)\t";
        echo "包装重量(g)\t";
        echo "包装尺寸(长*宽*高)CM\t";
        echo "产品首图\t";
        echo "业务开发员\t";
        echo "采购询价员\t";
        echo "采购员\t";

        foreach($spu_list as $k=>$v){
            echo "\n";
            echo $v['code']."\t";//产品编码
            echo iconv('utf-8', 'gbk', $v['name'])."\t";//产品名称
            echo "\t";//别名
            echo "\t";//属性名1
            echo "\t";//属性值1
            echo "\t";//属性名2
            echo "\t";//属性值2
            echo "\t";//属性编号
            echo "\t";//产品重量
            echo "\t";//采购单价
            echo "\t";//SKU属性别名
            echo "\t";//仓库名称1
            echo "\t";//库存数量1
            echo "\t";//货位1
            echo "\t";//仓库名称2
            echo "\t";//库存数量2
            echo "\t";//货位2
            echo "\t";//产品体积(长*宽*高)CM
            echo "\t";//产品特点
            echo iconv('utf-8', 'gbk', $v['remarks'])."\t";//备注
            echo iconv('utf-8', 'gbk', $v['supplier_name'])."\t";//供应商名称
            echo "\t";//最小采购量(MOQ)
            echo $v['source_address']."\t";//采购链接
            echo "\t";//分类
            echo "\t";//品牌
            echo "\t";//特性标签
            echo "\t";//中文配货名称
            echo "\t";//英文配货名称
            echo iconv('utf-8', 'gbk', $v['c_name'])."\t";//中文报关名
            echo $v['c_name_en']."\t";//英文报关名
            echo "\t";//包装材料名称
            echo "\t";//包装成本(CNY)
            echo "\t";//包装重量(g)
            echo "\t";//包装尺寸(长*宽*高)CM
            echo "\t";//产品首图
            echo "\t";//业务开发员
            echo "\t";//采购询价员
            echo "\t";//采购员

            foreach($v['sku_list'] as $i=>$item){
                echo "\n";
                echo "\t";//产品编码
                echo "\t";//产品名称
                echo "\t";//别名
                echo "规格\t";//属性名1
                echo iconv('utf-8', 'gbk', $item['norms'])."\t";//属性值1
                echo "\t";//属性名2
                echo "\t";//属性值2
                echo $item['code']."\t";//属性编号
                echo $item['weight']."\t";//产品重量
                echo $item['price']."\t";//采购单价
                echo iconv('utf-8', 'gbk', $item['alias'])."\t";//SKU属性别名
                echo "\t";//仓库名称1
                echo "\t";//库存数量1
                echo "\t";//货位1
                echo "\t";//仓库名称2
                echo "\t";//库存数量2
                echo "\t";//货位2
                echo "\t";//产品体积(长*宽*高)CM
                echo "\t";//产品特点
                echo "\t";//备注
                echo "\t";//供应商名称
                echo "\t";//最小采购量(MOQ)
                echo "\t";//采购链接
                echo "\t";//分类
                echo "\t";//品牌
                echo "\t";//特性标签
                echo "\t";//中文配货名称
                echo "\t";//英文配货名称
                echo "\t";//中文报关名
                echo "\t";//英文报关名
                echo "\t";//包装材料名称
                echo "\t";//包装成本(CNY)
                echo "\t";//包装重量(g)
                echo "\t";//包装尺寸(长*宽*高)CM
                echo "\t";//产品首图
                echo "\t";//业务开发员
                echo "\t";//采购询价员
                echo "\t";//采购员

            }
        }
    }

}