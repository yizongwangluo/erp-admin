<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>

<table class="layui-table">
    <thead>
    <th>ID</th>
    <th>广告位</th>
    <th>位置代码</th>
    <th>操作</th>
    </thead>
    <tbody>
	<?php foreach ( $data as $item ): ?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['alias']; ?></td>
            <td>
                <a href="<?php echo base_url ( "admin/ad/edit/{$item['id']}" ); ?>" class="layui-btn layui-btn-normal layui-btn-mini">编辑</a>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="admin-page">
	<?php echo $page_html; ?>
</div>
<?php $this->load->view ('admin/common/footer') ?>
