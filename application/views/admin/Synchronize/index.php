<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<form action="<?php echo site_url('admin/synchronize/synchronize_save'); ?>" method="post" class="layui-form">
        <div class="layui-inline">
            <div class="layui-input-inline">
                <input class="layui-input date-time" name="start_time" placeholder="开始时间" value="<?php echo input('start_time'); ?>">
            </div>
            <div class="layui-input-line">-</div>
            <div class="layui-input-inline">
                <input class="layui-input date-time" value="<?php echo input('end_time'); ?>" name="end_time" placeholder="结束时间">
            </div>
        </div>
        <div class="layui-inline  col-xs-2">
            <select name="shop" lay-search="">
                <option value="">直接选择或搜索选择同步店铺</option>
                <?php foreach ($shops as $v): ?>
                    <option value="<?=$v['id']?>" <?= $v['domain'] == $this->input->get ( 'shop' ) ? selected : '' ?> ><?=$v['domain']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button class="layui-btn layui-btn-danger btn-search" type="button" onclick="save_form()">同步订单</button>
</form>
<?php $this->load->view ( 'admin/common/footer' ) ?>

<script type="text/javascript">

    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //同时绑定多个
        lay('.date-time').each(function () {
            laydate.render({
                elem: this
                ,type: 'date'
                , trigger: 'click'
            });
        });
    });

    function save_form() {
        var index = layer.load();
        var form = $('form'),index = layer.load(),data = form.serializeArray();
        $.post(form.attr('action'), data , function (response) {
            if(!response.status){
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('同步成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/synchronize/index';
                })
            }
        });
    }
</script>