<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    em{
        color: red;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?=base_url('admin/goods_warehouse/index')?>">仓库列表</a></li>
        <li  class="layui-this">新增仓库</li>
    </ul>
<form action="<?php echo base_url ( 'admin/goods_warehouse/save' ) ?>" method="post" class="layui-form">
    <div class="layui-field-box">
    <input type="hidden" name="id" value="<?= $info['id'] ?>">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*仓库名称：</label>
            <div class="layui-inline">
                <input name="name" lay-verify="required" value="<?= $info['name'] ?>" type="text" class="layui-input">
            </div>
        </div>
        </div>
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*状态：</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="开启" <?=$info['status']!=2?'checked':'';?>>
                <input type="radio" name="status" value="2" title="关闭" <?=$info['status']==2?'checked':'';?>>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" onclick="save_form()">保存</button>
        </div>
    </div>
    </div>
</form>
</div>
<script type="text/javascript">
    function save_form() {
        var form = $('form'),index = layer.load();
        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location.href = '<?php echo site_url ( 'admin/goods_warehouse/index' ); ?>';
                })
            }
        }, 'json');
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>