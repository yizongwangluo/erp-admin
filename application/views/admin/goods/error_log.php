<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?php echo base_url ( 'admin/goods/index' ) ?>">商品列表</a></li>
        <li><a href="<?php echo base_url ( 'admin/goods/addexcel' ) ?>">导入</a></li>
        <li class="layui-this">导入错误日志</li>
    </ul>
    <div class="layui-tab-content">
            <table class="layui-table">
              <thead>
                <tr>
                    <td>ID</td>
                    <td>文件名</td>
                    <td>时间</td>
                    <td>操作人ID</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$v['name']?></td>
                      <td><?=$v['datetime']?></td>
                      <td><?=$v['u_id']?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs" href="/upload/errorexcel/<?=$v['content']?>">下载</a>
                          <button style="display: <?=$v['status']==1?'none':'';?>;" data-url="<?php echo base_url ( 'admin/goods/error_log_del' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>
                      </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
            </table>
    </div>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>
