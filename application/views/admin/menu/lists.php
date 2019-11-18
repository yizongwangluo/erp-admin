<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
    <div class="layui-tab admin-layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this">菜单列表</li>
            <li class=""><a href="<?php echo base_url ( 'admin/menu/add' ) ?>">新增菜单</a></li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>菜单名称</th>
                        <th>排序</th>
                        <th>地址</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( $list as $vo ): ?>
                        <tr>

                            <td><?= $vo['id'] ?></td>
                            <td><?php if ($vo['level'] != 1){ echo '|';
                                for ($i=1;$i < $vo['level'];$i++){
		                            echo ' ----';
                                }
                               }?><?=$vo['name']?>
                            </td>
                            <td><?= $vo['sort'] ?></td>
                            <td><?= $vo['url'] ?></td>
                            <td><?= empty( $vo['status'] ) ? '不显示' : '显示' ?></td>
                            <td>
                                <a href="<?php echo base_url ( 'admin/menu/edit/' . $vo['id'] ) ?>"
                                   class="layui-btn layui-btn-primary layui-btn-mini">编辑</a>
                                <button data-url="<?php echo base_url ( 'admin/menu/del' ) ?>" data-id="<?= $vo['id'] ?>" class="layui-btn layui-btn-mini layui-btn-danger confirm_post">删除
                                </button>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $this->load->view ( 'admin/common/footer' ) ?>