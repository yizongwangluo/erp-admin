<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
    <style>
        em{
            color: red;
        }
        .boder-inline{
            border: 1px solid #000000;
            word-wrap:break-word;
            height: 70px;

        }
    </style>
    <div class="layui-tab admin-layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li  class="layui-this">订单详情</li>
        </ul>
        <form method="post" class="layui-form">
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">ID：</label>
                        <label class="layui-form-label"><?=$info['id'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">shopfiy订单号：</label>
                        <label class="layui-form-label"><?=$info['shopify_o_id'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">运单号：</label>
                        <label class="layui-form-label"><?=$info['tracking_number'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">总价：</label>
                        <label class="layui-form-label"><?=$info['total_price_usd'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">创建时间：</label>
                        <label class="layui-form-label"><?=$info['created_at'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">修改时间：</label>
                        <label class="layui-form-label"><?=$info['updated_at'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">总重量 （g）：</label>
                        <label class="layui-form-label"><?=$info['total_weight'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">金融状态：</label>
                        <label class="layui-form-label"><?=$info['financial_status'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">店铺：</label>
                        <label class="layui-form-label"><?=$shoplist[$info['shop_id']]['domain'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">日期：</label>
                        <label class="layui-form-label"><?=$info['datetime'];?></label>
                    </div>
                    <div class="layui-inline boder-inline">
                        <label class="layui-form-label">数据插入时间：</label>
                        <label class="layui-form-label"><?=$info['addtime'];?></label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">订单sku列表：</label>
                    <div class="layui-inline">
                        <table class="layui-hide" id="test" lay-filter="test" ></table>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" type="button" onclick="javascript:history.back(-1);">返回</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="px">
            <script type="text/html" id="barDemo">
                <a class="layui-btn layui-btn-xs" lay-event="info">查看</a>
            </script>
        </div>
    </div>

    <script type="text/javascript">

        layui.use('table', function(){
            var table = layui.table;

            //sku列表
            table.render({
                elem: '#test'
                ,id: 'idTest'
                ,url:'/admin/order/orderGoodslist/<?=$info['id']?>'
                ,defaultToolbar: []
                ,limit:9999
                ,cellMinWidth: 120 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', title: 'ID' }
                    ,{field:'product_id',width:150,   title: 'shopify商品ID'}
                    ,{field:'sku_id',  title: 'sku'}
                    ,{field:'quantity',  title: '购买数量'}
                    ,{field:'datetime',  title: '时间'}
                    ,{field:'addtime',  title: '添加时间'}
                ]]
            });
        });

    </script>
<?php $this->load->view ( 'admin/common/footer' ) ?>