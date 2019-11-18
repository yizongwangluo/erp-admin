<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>

<div class="layui-tab admin-layui-tab layui-tab-brief">
	<ul class="layui-tab-title">
		<li class="layui-this">权限组列表</li>
		<li class="" data-modal="<?php echo base_url ( 'admin/auth_group/add' ) ?>"  data-title="新增权限组" data-width="450px">新增权限组</li>
	</ul>
	<div class="layui-tab-content">
		<div class="layui-tab-item layui-show">
			<table class="layui-table">
				<thead>
				<th>ID</th>
				<th>名称</th>
				<th>状态</th>
				<th>操作</th>
				</thead>
				<tbody>
				<?php foreach ($data as $item): ?>
					<tr>
						<td><?=$item['id'];?></td>
						<td><?=$item['title'];?></td>
						<td><?=$item['status'] == 1 ? '启用':'禁用'?></td>
						<td>
                            <button class="layui-btn-mini layui-btn" type="button"  data-modal="<?php echo base_url ( 'admin/auth_group/edit/'.$item['id'] ) ?>"  data-title="编辑权限组" data-width="450px">编辑</button>
                            <button data-modal="<?php echo base_url ( 'admin/auth_group/auth_node/'.$item['id']  ) ?>" data-title="节点授权" data-width="450px" class="layui-btn layui-btn-mini layui-btn-normal <?php if ($item['id'] == 1){ echo 'layui-hide';} ?>">授权</button>
                            <button class="layui-btn layui-btn-mini layui-btn-danger confirm_get <?php if ($item['id'] == 1){ echo 'layui-hide';} ?>" data-url="<?=base_url ('admin/auth_group/del/'.$item['id'])?>" >删除</button>
						</td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="admin-page">
	<?php echo $page_html; ?>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

