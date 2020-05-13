<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?php echo base_url ( '/admin/order/Import' ) ?>">运费导入</a></li>
        <li class="layui-this">失败日志</li>
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
                          <a class="layui-btn layui-btn-xs" href="/admin/goods/error_dow/<?=$v['id']?>">下载</a>
                          <button style="display: <?=$v['status']==1?'none':'';?>;" data-url="<?php echo base_url ( 'admin/goods/error_log_del' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>
                      </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
            </table>
    </div>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>
