<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>

<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">选科要求列表</li>
        <li><a href="<?php echo base_url('admin/demand/add') ?>">新增</a></li>
        <li><a href="<?php echo base_url('admin/demand/addexcel') ?>">导入</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <table class="layui-table">
                <thead>
                <th>ID</th>
                <th>选考科目要求</th>
                <th>操作</th>
                </thead>
                <tbody>
                <?php if($list['data']){ foreach ($list['data'] as $item){ ?>
                    <tr>
                        <td><?=$item['id'];?></td>
                        <td><?=$item['info'];?></td>
                        <td>
                            <a class="layui-btn-mini layui-btn" href="<?php echo base_url('admin/demand/edit/'.$item['id']) ?>">编辑</a>
                        </td>
                    </tr>
                <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="admin-page">
    <?php echo $page_html; ?>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

