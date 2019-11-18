<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">默认信息管理</li>
    </ul>
</div>
<div style="padding-top:20px; ">
        <form action="<?php echo site_url('admin/default_mobile/save'); ?>" class="layui-form" method="post">
            <input name = 'id' value="<?php echo $info['id']; ?>" type="hidden" >
            <div class="layui-form-item">
                <label class="layui-form-label">省份：</label>
                <div class="layui-inline col-xs-3">
                    <select name="province_id" lay-verify="required" lay-search=""  lay-filter="province_id" >
                        <option value="">请选择</option>
                        <?php foreach($province_redis as $value){ ?>
                            <option value="<?php echo $value['id'] ?>" <?php if($info['province_id']==$value['id']){ echo 'selected'; } ?> <?php echo $value['status']!=1?'disabled':''; ?> ><?php echo $value['name'] ?></option>
                        <?php   } ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">年份：</label>
                <div class="layui-inline col-xs-3">
                    <select name="year_manager_id" lay-verify="required" lay-search="" id="year_manager_id" <?php echo count($province_id_list)>0 ? '':'disabled="disabled"'; ?>>
                        <option value="">请选择</option>
                        <?php foreach($province_id_list as $value){ ?>
                            <option value="<?php echo $value['year_id'] ?>" <?php if($info['year_manager_id']==$value['id']){ echo 'selected'; } ?> <?php echo $value['status']!=1?'disabled':''; ?> ><?php echo $value['year'] ?></option>
                        <?php   } ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" type="button" onclick="save_form()" >提交</button>
                </div>
            </div>
        </form>
    </div>
<script>
    layui.use(['form','laydate'], function () {
        var form = layui.form,year_manager_html;

        //根据省份查找年份
        form.on('select(province_id)',function (data) {

            var id =data.value ?  data.value:0;

            $.post('/admin/info_rele/yearlist_in_province',{id:id}, function (response) {
                year_manager_html = '';
                if (response.status) {
                    $.each(response.data,function(i,val) {
                        year_manager_html +='<option value="'+val.year_id+'" >'+val.year+'</option>';
                    });
                    $('#year_manager_id').removeAttr('disabled');//移除状态
                }else{
                    $('#year_manager_id').attr("disabled","disabled");//添加不可读写状态
                }

                $('#year_manager_id').html(year_manager_html);
                form.render('select');
            });
        });
    });

    function save_form() {
        var index = layer.load();
        var form = $('form');
        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location = '/admin/default_mobile/add';
                })
            }
        });
    }
</script>
<?php $this->load->view('admin/common/footer')?>