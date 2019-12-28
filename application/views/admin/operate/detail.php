<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class=""><a href='<?php echo base_url ( 'admin/operate/index' ) ?>'>运营数据列表</a></li>
        <li class="layui-this">运营数据详情</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <div class="layui-form-item">
                <label class="layui-form-label">日期：</label>
                <div class="layui-inline">
                     <div class="detail">
                        <?= $info['datetime'] ?>
                     </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">店铺域名：</label>
                <div class="layui-inline">
                    <div class="detail">
                        <?= $domain['domain'] ?>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">店铺负责人：</label>
                <div class="layui-inline">
                    <div class="detail">
                        <?= $user['user_name'] ?>（<?= $user['real_name'] ?>）
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">汇率：</label>
                <div class="layui-inline">
                    <div class="detail">
                        <?= $info['exchange_rate'] ?>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">营业额（$）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['turnover'] ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">广告费（$）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['ad_cost'] ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手续费点数：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['service_charge']*0.01 ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手续费（$）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['formalities_cost'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">产品总成本（¥）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['product_total_cost'] ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">付款订单数：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['paid_orders'] ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">挂号费（¥）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['register_cost'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin: 0 10px">
                <table class="layui-hide" id="product_list"></table>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">毛利（$）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['gross_profit'] ?>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">毛利（¥）：</label>
                    <div class="layui-inline">
                        <div class="detail">
                            <?= $info['gross_profit_rmb'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">毛利率：</label>
                <div class="layui-inline">
                    <div class="detail">
                        <?= $info['gross_profit_rate'] ?>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">ROI：</label>
                <div class="layui-inline">
                    <div class="detail">
                        <?= $info['ROI'] ?>
                    </div>
                </div>
            </div>
        </div>
        <a class="layui-btn-sm layui-btn layui-btn-normal" type="button"  href="<?php echo $_SERVER['HTTP_REFERER']; ?>" style="position:absolute;right: 20%">返回上一页</a>

    </div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>
<style>
    .layui-form-item{
        margin-bottom: 0;
    }

    .layui-form-label{
        width: 120px;
        text-align: left;
    }

    .detail{
        padding: 10px;
    }
</style>
<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#product_list'
            ,url:'/admin/operate/product_list/<?=$info['id']?>'
            ,cellMinWidth: 80 //
            ,cols: [[
                {field:'id', width:80, title: '序号'}
                ,{field:'name', title: '产品名'}
                ,{field:'sku_id', title: 'SKU'}
                ,{field:'quantity', title: '出单产品数量'}
                ,{field:'price', title: '采购价（¥）'}
                ,{field:'weight', title: '产品重量（g）'}
                ,{field:'freight', title: '每克价格（¥）'}
                ,{field:'product_cost', title: '产品成本（¥）'}
            ]]
        });
    });
</script>
