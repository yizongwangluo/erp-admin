<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/auth_group/add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" value="<?=$info['title']?>" required lay-verify="required" placeholder="请输入权限组名称" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="启用" <?php if ($info['status'] == 1 || empty($info)):?>checked="checked"<?php endif; ?> >
            <input type="radio" name="status" value="0" title="禁用" <?php if (is_numeric ($info['status']) && $info['status'] == 0):?>checked="checked"<?php endif; ?> >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/auth_group/lists' ) ?>" lay-submit lay-filter="post">保存</button>
        </div>
    </div>
</form>
