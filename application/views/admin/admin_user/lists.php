<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>

<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">账户列表</li>
        <li data-modal="<?php echo base_url ( 'admin/admin_user/add' ) ?>" data-width="450px" data-title="新增账户">新增账户</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <table class="layui-table">
                <thead>
                <th>ID</th>
                <th>用户名</th>
                <th>真实姓名</th>
                <th>最后登陆时间</th>
                <th>是否禁用</th>
                <th>操作</th>
                </thead>
                <tbody>
                <?php foreach ($data as $item): ?>
                <tr>
                    <td><?=$item['id'];?></td>
                    <td><?=$item['user_name'];?></td>
                    <td><?=$item['real_name'];?></td>
                    <td><?=date ('Y-m-d H:i:s',$item['login_time'])?></td>
                    <td><?=$item['is_disable'] == 1? '是':'否'?></td>
                    <td>
                        <button class="layui-btn-mini layui-btn" type="button" data-modal="<?php echo base_url ( 'admin/admin_user/edit/'.$item['id'] ) ?>" data-width="450px" data-title="编辑管理员">编辑</button>
                        <button data-modal="<?php echo base_url ( 'admin/admin_user_org/auth_node/'.$item['id']  ) ?>" data-title="授权" data-width="450px" class="layui-btn layui-btn-mini layui-btn-normal">授权</button>
                        <button class="layui-btn layui-btn-mini layui-btn-danger confirm_get" data-url="<?=base_url ('admin/admin_user/del/'.$item['id'])?>" >删除</button>
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

