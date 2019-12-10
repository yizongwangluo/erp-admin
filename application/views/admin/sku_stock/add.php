<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    em{
        color: red;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?=base_url('admin/sku_stock/lists')?>">我的申请</a></li>
        <li  class="layui-this">新增</li>
    </ul>
<form action="<?php echo base_url ( 'admin/sku_stock/save' ) ?>" method="post" class="layui-form">
    <div class="layui-field-box">
    <input type="hidden" name="id" value="<?= $info['id'] ?>">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*sku编号：</label>
            <div class="layui-inline">
                <input name="sku_id" lay-verify="required" value="<?= $info['sku_id'] ?>" type="number" class="layui-input">
            </div>
        </div>
    </div>
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*补货数：</label>
            <div class="layui-inline">
                <input name="add_sku_number" lay-verify="required" value="<?= $info['add_sku_number'] ?>" type="number" class="layui-input">
            </div>
        </div>
    </div>
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*备货天数：</label>
            <div class="layui-inline">
                <input name="days" lay-verify="required" value="<?= $info['days'] ?>" type="number" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">运营备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="<?= $info['remarks'] ?>" type="text" class="layui-input">
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
        var form = $('form');
        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location.href = '<?php echo site_url ( 'admin/sku_stock/lists' ); ?>';
                })
            }
        }, 'json');
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>