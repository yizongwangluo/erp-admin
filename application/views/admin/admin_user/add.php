<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/admin_user/add' ) ?>" method="post">
    <input type="hidden" value="<?=$user_info['id']?>" name="user_id">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="user_name" value="<?=$user_info['user_name']?>" required lay-verify="required" placeholder="请输入用户名"  class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" name="password" value="" placeholder="请输入密码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-block">
            <input type="text" name="real_name" value="<?=$user_info['real_name']?>" required lay-verify="required" placeholder="请输入真实姓名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属权限组</label>
        <div class="layui-input-block">
            <select name="role_id" lay-verify="required">
                <option value="">请选择所属权限组</option>
                <?php foreach ($auth_group_list as $item): ?>
                <option value="<?=$item['id']?>" <?php if ($user_info['role_id'] == $item['id'] ): ?> selected="selected" <?php endif; ?> ><?=$item['title']?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="启用" <?php if (intval ($user_info['is_disable']) == 0 ): ?> checked="checked" <?php endif; ?> >
            <input type="radio" name="status" value="1" title="禁用" <?php if ($user_info['is_disable'] ==1 && is_numeric ($user_info['is_disable'])): ?> checked="checked" <?php endif; ?> >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" data-url="<?= site_url ( 'admin/admin_user/lists' ) ?>" lay-submit lay-filter="post">保存
            </button>
        </div>
    </div>
</form>
