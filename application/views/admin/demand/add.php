<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?php echo base_url('admin/demand/lists') ?>">选科要求列表</a></li>
        <li class="layui-this">新增</li>
        <li><a href="<?php echo base_url('admin/demand/addexcel') ?>">导入</a></li>
    </ul>
</div>
<div style="padding-top:20px; ">
        <form action="<?php echo site_url('admin/demand/save'); ?>" class="layui-form" method="post">
            <input name = 'id' value="<?php echo $info['id']; ?>" type="hidden" >
            <div class="layui-form-item">
                <label class="layui-form-label">描述：</label>
                <div class="layui-inline">
                    <input name="info"   value="<?php echo $info['info']; ?>" type="text" class="layui-input" style="width: 600px;">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">要求：</label>
                <div class="layui-inline" style="width: 600px;">
                    <select name="demand_ask_id" lay-verify="required" lay-search="">
                        <option value="">请选择</option>
                        <?php foreach($this->enum_field->get_values('demand_ask') as $key=>$value){ ?>
                            <option value="<?php echo $key ?>" <?php if($info['demand_ask_id']==$key){ echo 'selected'; } ?> ><?php echo $value ?></option>
                        <?php   } ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">关联科目：</label>
                <div class="layui-inline">
                    <?php foreach($subjects_redis as $value){ ?>
                        <input type="checkbox" name="subjects_id[<?php echo $value['id'] ?>]" lay-skin="primary" title="<?php echo $value['name'] ?>" <?php echo in_array($value['id'],$info['subjects_id_arr']) ? 'checked':''; ?> <?php echo $value['status']!=1 ? 'disabled':''; ?> >
                    <?php } ?>
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
        var form = $('form');
        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/demand/lists';
                })
            }
        });
    }
</script>
<?php $this->load->view('admin/common/footer')?>