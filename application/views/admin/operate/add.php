<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/operate/add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">广告费用</label>
        <div class="layui-input-block">
            <input type="text" name="ad_cost" value="<?=$info['ad_cost']?>" placeholder="请输入金额" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/operate/index' ) ?>" lay-submit lay-filter="post">保存</button>
            <button type="button" class="layui-btn layui-layer-close">取消</button>
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