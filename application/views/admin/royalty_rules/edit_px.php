<?php $this->load->view ( 'admin/common/header' ) ?>
<style>
    .w {
        width: 125px;
    }
</style>
<form action="/admin/royalty_rules/save_px" method="post" class="layui-form" id="add_px">
    <div class="layui-field-box">
        <input type="hidden" value="" name="id">
        <input type="hidden" value="" name="r_id">
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label w">*营业额（最小）：</label>
            <div class="layui-inline">
                <input name="range_start" lay-verify="required" value="0" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label w">*营业额（最大）：</label>
            <div class="layui-inline">
                <input name="range_end" lay-verify="required" value="" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label w">	系数：</label>
            <div class="layui-inline">
                <input name="ratio" lay-verify="required" value="0" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label w">备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="" type="text" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" onclick="save_form_px()">确定</button>
        </div>
    </div>
    </div>
</form>

<script>

    function save_form_px() {
        if(!$('input[name="range_end"]').val()){
            layer.msg('营业额（最大） 必填', {icon: 5});
            return false;
        }

        var form = $('#add_px');
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6})
                window.parent.table.reload('idTest'); //重载 table
                parent.layer.close(index); //再执行关闭
            }

        },'json');
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>