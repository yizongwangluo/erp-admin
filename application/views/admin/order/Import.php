<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">运费导入</li>
        <li><a href="/admin/order/error_log">失败日志</a></li>
    </ul>
</div>
<div style="margin-top: 30px;">
    <form action="<?php echo site_url('admin/order/import_save'); ?>" class="layui-form" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">excel文件：</label>
            <div class="layui-inline">
                <div class="layui-inline" style="min-width: 420px"><input id="thumb_file"  name="file_name" value="" type="text" class="layui-input" /></div>
                <div class="layui-inline"><button type="button" class="layui-btn upload-file-all"><i class="layui-icon"></i>上传文件</button></div>
            </div>
            <em><a href="/import_tmp.xlsx">下载导入模板</a></em>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" type="button" onclick="save_form()" >提交</button>
            </div>
        </div>
    </form>
</div>
<script>
    function save_form() {
        var index = layer.load();
        var form = $('form');
        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if(!response.status){
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
            } else {
                layer.msg('导入成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/order/index';
                })
            }
        });
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>
