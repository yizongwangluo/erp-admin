<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class=""><a href="<?php echo base_url ( 'admin/menu/lists' ) ?>">菜单列表</a></li>
        <li class="layui-this">新增菜单</li>
    </ul>
<div class="layui-tab-item layui-show">
    <form action="<?php echo base_url ( 'admin/menu/save' ) ?>" method="post" class="layui-form">
        <div class="layui-field-box">
        <input type="hidden" name="id" value="<?= $info['id'] ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">*上级：</label>
            <div class="layui-inline col-xs-3">
                <select name="pid">
                    <option value="">作为一级菜单</option>
		            <?php foreach ( $list as $v ): ?>
                        <option value="<?= $v['id'] ?>" <?php if ( $info['pid'] == $v['id'] ): ?> selected="selected" <?php endif; ?> ><?php if ($v['level'] != 1){ echo '|';
		                        for ($i=1;$i < $v['level'];$i++){
			                        echo ' ----';
		                        }
	                        }?><?= $v['name'] ?></option>
		            <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">*菜单名称：</label>
            <div class="layui-inline col-xs-3">
                <input name="name" lay-verify="required" value="<?= $info['name'] ?>" type="text" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">URL地址：</label>
            <div class="layui-inline col-xs-3">
                <input name="url" value="<?= $info['url'] ?>"type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">*状态</label>
            <div class="layui-inline col-xs-3">
                <input <?php if ($info['status'] == 1): ?>checked="checked"<?php endif; ?>
                       class="checkbox select_store_type_tag" type="radio" value="1" name="status" title="显示">
                <input <?php if ($info['status'] == 0): ?>checked="checked"<?php endif; ?>
                       class="checkbox select_store_type_tag" type="radio" value="0" name="status" title="不显示">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序：</label>
            <div class="layui-inline col-xs-3">
                <input name="sort" value="<?= $info['sort'] ?>" type="number" class="layui-input">
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
                        window.location.href = '<?php echo site_url ( 'admin/menu/lists' ); ?>';
                    })
                }
            }, 'json');
        }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>