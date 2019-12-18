<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/operate/add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <div class="layui-form-item">
        <label class="layui-form-label">日期</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" id="date" placeholder="请选择日期" name="date" value="<?= $info['date'] ? date('Y-m-d',$info['date']) : '' ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">店铺域名</label>
        <div class="layui-input-block">
            <select name="shop_id" lay-search="" required lay-verify="required">
                <option value="">直接选择或搜索选择</option>
                <?php foreach ($domains as $v): ?>
                    <option value="<?=$v['id']?>" <?php if ( $info['shop_id'] == $v['id'] ){echo "selected=\"selected\"";}?>><?=$v['domain']?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">付款订单数</label>
        <div class="layui-input-block">
            <input type="text" name="paid_orders" value="<?=$info['paid_orders']?>" required lay-verify="required" placeholder="请输入付款订单数" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">营业额</label>
        <div class="layui-input-block">
            <input type="text" name="turnover" value="<?=$info['turnover']?>" required lay-verify="required" placeholder="请输入营业额" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">sku总成本</label>
        <div class="layui-input-block">
            <input type="text" name="sku_total_cost" value="<?=$info['sku_total_cost']?>" required lay-verify="required" placeholder="请输入sku总成本" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">广告费用</label>
        <div class="layui-input-block">
            <input type="text" name="ad_cost" value="<?=$info['ad_cost']?>" placeholder="请输入金额" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/operate/index' ) ?>" lay-submit lay-filter="post">保存</button>
            <a href='<?php echo base_url ( 'admin/operate/index' ) ?>'><button type="button" class="layui-btn ">取消</button></a>
        </div>
    </div>
</form>

<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //常规用法
        laydate.render({
            elem: '#date'
        });
    });
</script>