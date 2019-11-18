<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/home/changemepassword' ) ?>" method="post">
	<div class="layui-form-item">
		<label class="layui-form-label">原密码</label>
		<div class="layui-input-block">
			<input type="password" name="old_password" value="" required lay-verify="required"  placeholder="请输入原来的密码" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">新密码</label>
		<div class="layui-input-block">
			<input type="password" name="new_password" value="" required lay-verify="required"  placeholder="请输入新的密码" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">真实姓名</label>
		<div class="layui-input-block">
			<input type="text" name="real_name" value="<?=$user_info['real_name']?>" required lay-verify="required" placeholder="请输入真实姓名" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" type="button" data-url="<?= site_url ( 'admin' ) ?>" lay-submit lay-filter="post">保存
			</button>
		</div>
	</div>
</form>
