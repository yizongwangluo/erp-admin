<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<form action="?" method="get">
    <div class="layui-form">
        <div class="layui-inline  col-xs-2">
            <input type="text" name="username" value="<?php echo $this->input->get ( 'username' ); ?>"
                   class="layui-input" placeholder="输入管理员登陆用户名"/>
        </div>
        <div class="layui-inline">
            <div class="layui-input-inline">
                <input class="layui-input date-time" name="start_time" placeholder="开始时间" value="<?php echo input('start_time'); ?>">
            </div>
            <div class="layui-input-line">-</div>
            <div class="layui-input-inline">
                <input class="layui-input date-time" value="<?php echo input('end_time'); ?>" name="end_time" placeholder="截止时间">
            </div>
        </div>
        <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
    </div>
    <table class="layui-table">
      <thead>
        <tr>
            <td>ID</td>
            <td>用户名</td>
            <td>行为名称</td>
            <td>URL</td>
            <td>请求方式</td>
<!--            <td  width="30%">参数</td>-->
            <td>IP</td>
            <td>时间</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $v): ?>
          <tr>
              <td><?=$v['id']?></td>
              <td><?=$v['username']?></td>
              <td><?=$v['title']?></td>
              <td><?=$v['url']?></td>
              <td><?=$v['request']?></td>
<!--              <td>--><?//=$v['content']?><!--</td>-->
              <td><?=$v['ip']?></td>
              <td><?=date ('Y-m-d H:i:s',$v['dateline'])?></td>
          </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</form>
<div class="admin-page">
	<?php echo $page_html; ?>
</div>

<script type="text/javascript">

    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //同时绑定多个
        lay('.date-time').each(function () {
            laydate.render({
                elem: this
                , trigger: 'click'
            });
        });
    });
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>
