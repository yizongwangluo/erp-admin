<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?php echo base_url('admin/demand/lists') ?>">选科要求列表</a></li>
        <li><a href="<?php echo base_url('admin/demand/add') ?>">新增</a></li>
        <li  class="layui-this">导入</li>
    </ul>
</div>
<div style="margin-top: 30px;">
    <form action="<?php echo site_url('admin/demand/addexcel_save'); ?>" class="layui-form" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">excel文件：</label>
            <div class="layui-inline">
                <div class="layui-inline" style="min-width: 420px"><input id="thumb_file"  name="file_name" value="" type="text" class="layui-input" /></div>
                <div class="layui-inline"><button type="button" class="layui-btn upload-file-all"><i class="layui-icon"></i>上传文件</button></div>
            </div>
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

                if(Object.keys(response.data).length){
                    window.location = '/admin/demand/export_excel/'+response.data['errRedisName'];
                }
                layer.close(index);
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/demand/lists';
                })
            }
        });
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>
