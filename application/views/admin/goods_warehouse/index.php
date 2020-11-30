<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">仓库列表</li>
        <li><a href="<?=base_url('admin/goods_warehouse/add')?>">新增仓库</a></li>
    </ul>
    <div class="layui-tab-content">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline">
                        <select name="status" lay-verify="required">
                            <option value="">请选择</option>
                            <option value="1" <?=input('status')==1?'selected':''?>>开启</option>
                            <option value="2" <?=input('status')==2?'selected':''?>>关闭</option>
                        </select>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
            </form>
            <table class="layui-table">
              <thead>
                <tr>
                    <td>ID</td>
                    <td>仓库名称</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$v['name']?></td>
                      <td><?=$v['status']==1?'开启':'关闭'?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods_warehouse/edit/{$v['id']}"); ?>">编辑</a>
                          <button data-url="<?php echo base_url ( 'admin/goods_warehouse/del' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs confirm_post layui-btn-danger">删除</button>
                      </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <div class="admin-page">
            <?php echo $page_html; ?>
        </div>
    </div>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>
