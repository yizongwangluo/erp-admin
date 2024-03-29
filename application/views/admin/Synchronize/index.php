<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<form action="<?php echo site_url('admin/synchronize/synchronize_save'); ?>" method="post" class="layui-form" id="form">
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
                    <option value="<?=$v['id']?>" <?= $v['domain'] == $this->input->get ( 'shop' ) ? 'selected' : '' ?> <?=$v['status']==1?'':'disabled';?> ><?=$v['domain']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button class="layui-btn layui-btn-danger btn-search" type="button" onclick="save_form()">同步订单</button>
    <em style="color: red">* 只同步付款状态为已支付的订单</em>
</form>
<br>
<br>
<form action="<?php echo site_url('admin/synchronize/synchronize_save_mb'); ?>" method="post" class="layui-form" id="form_mb">
        <div class="layui-inline">
            <div class="layui-input-inline">
                <input class="layui-input date-time" name="start_time" placeholder="开始时间" value="<?php echo input('start_time'); ?>">
            </div>
            <div class="layui-input-line">-</div>
            <div class="layui-input-inline">
                <input class="layui-input date-time" value="<?php echo input('end_time'); ?>" name="end_time" placeholder="结束时间">
            </div>
        </div>
        <button class="layui-btn layui-btn-danger btn-search" type="button" onclick="save_form_mb()">同步马帮订单</button>
    <em style="color: red">* 上午7:00~19:00时间范围只支持近一天数据查询,其余时间只允许时间范围为一天的数据</em>
</form>
<br>
<br>
<form action="<?php echo site_url('admin/synchronize/up_operate'); ?>" method="post" class="layui-form" id="form0">
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
                <option value="<?=$v['id']?>" <?= $v['domain'] == $this->input->get ( 'shop' ) ? 'selected' : '' ?> <?=$v['status']==1?'':'disabled';?> ><?=$v['domain']?></option>
            <?php endforeach;?>
        </select>
    </div>
    <button class="layui-btn layui-btn-danger btn-search" type="button" onclick="up_form()">更新运营数据</button>
</form>
<br>
<br>
<form action="<?php echo site_url('admin/synchronize/repair_order_time'); ?>" method="post" class="layui-form" id="form1">
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
        <select name="shop_id" lay-search="">
            <option value="">直接选择或搜索选择同步店铺</option>
            <?php foreach ($shops as $v): ?>
                <option value="<?=$v['id']?>" <?= $v['domain'] == $this->input->get ( 'shop' ) ? 'selected' : '' ?> <?=$v['status']==1?'':'disabled';?> ><?=$v['domain']?></option>
            <?php endforeach;?>
        </select>
    </div>
    <button class="layui-btn layui-btn-danger btn-search" type="button" onclick="repair_order_time()">更新错误订单时间</button>
</form>

<style>
    .chuangkou{
        width: 350px;
        height: 250px;
        background-color: #8cd2ea;
        z-index: 99999;
        position:absolute;
        right: 30px;
        bottom: 0;
        border: 1px solid #988d8d;

    }
    .chuangkou h3{
        margin-left: 20px;
    }
    .duilie-ul{
        height: 208px;
        overflow-y:auto;
    }
    .duilie-ul li{
        list-style: decimal;
        margin: 0  15px 5px  30px ;
    }
</style>
<div class="chuangkou">
    <h3>同步订单队列</h3>
    <hr/>
    <ul  class="duilie-ul">
        <li>暂无队列信息</li>
    </ul>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>


<script type="text/javascript">

    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //同时绑定多个
        lay('.date-time').each(function () {
            laydate.render({
                elem: this
                ,type: 'datetime'
                , trigger: 'click'
            });
        });

        get_duilie();
    });

    function get_duilie(){
        $.get('/duilie.json',function(obj){
            if(obj.code==1){

                if(obj.data.length){

                    var html = '';

                    $.each(obj.data, function(index, value) {
                        html +='<li>店铺：'+value.domain+',开始时间：'+value.start_time+',结束时间：'+value.end_time+'</li>';
                    });
                    $('.duilie-ul').html(html);

                }
            }
        })
    }

    function save_form() {
        var index = layer.load();
        var form = $('#form'),index = layer.load(),data = form.serializeArray();
        $.post(form.attr('action'), data , function (response) {
            if(!response.status){
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('已加入同步队列', {time: 2000, icon: 6}, function () {
                    get_duilie();
                    window.location = '/admin/synchronize/index';
                })
            }
        });
    }

    function save_form_mb() {
        var index = layer.load();
        var form = $('#form_mb'),index = layer.load(),data = form.serializeArray();
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

    function up_form() {
        var form0 = $('#form0'),index = layer.load(),data0 = form0.serializeArray();
        $.post(form0.attr('action'), data0 , function (response) {
            if(!response.status){
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('更新成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/synchronize/index';
                })
            }
        });
    }

    function repair_order_time() {
        var form0 = $('#form1'),index = layer.load(),data0 = form0.serializeArray();
        $.post(form0.attr('action'), data0 , function (response) {
            if(!response.status){
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('更新成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/synchronize/index';
                })
            }
        });
    }
</script>