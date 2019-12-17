<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    em{
        color: red;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?php echo base_url ( 'admin/royalty_rules/index' ) ?>">提成规则列表</a></li>
        <li class="layui-this">新增规则</li>
    </ul>
<form action="<?php echo base_url ( 'admin/royalty_rules/save' ) ?> " method="post" class="layui-form">
    <div class="layui-field-box">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*选择部门：</label>
            <div class="layui-inline">
                <select name="o_id" lay-search>
                    <option value="">请选择部门</option>
                    <?php foreach ( $olist as $v ): ?>
                        <option value="<?= $v['id'] ?>"  ><?php if ($v['level'] != 1){ echo '|';
                                for ($i=1;$i < $v['level'];$i++){
                                    echo ' --';
                                }
                            }?><?= $v['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*手续费：</label>
            <div class="layui-inline">
                <input name="service_charge" lay-verify="required" value="" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">	*运费(g)：</label>
            <div class="layui-inline">
                <input name="freight" lay-verify="required" value="" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*挂号费：</label>
            <div class="layui-inline">
                <input name="register_fee" lay-verify="required" value="" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="" type="text" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">提成系数（PX）：</label>
        <div class="layui-inline">
            <table class="layui-hide" id="test" lay-filter="test"></table>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">提成系数（GMP）：</label>
        <div class="layui-inline">
            <table class="layui-hide" id="test2" lay-filter="test2"></table>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" onclick="save_form()">保存</button>
        </div>
    </div>
    </div>
</form>
    <div class="px">
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button type="button" class="layui-btn layui-btn-sm" data-modal="<?php echo base_url ( 'admin/royalty_rules/add_px' ) ?>"  data-title="提成系数（px）" data-width="450px"><i class="layui-icon">&#xe654;</i></button>
            </div>
        </script>

        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs"  lay-event="edit" data-modal="<?php echo base_url ( 'admin/royalty_rules/add_px?type=edit' ) ?>" data-title="提成系数（px）" data-width="450px">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
    <div class="gmp">
        <script type="text/html" id="toolbarDemo2">
            <div class="layui-btn-container">
                <button type="button" class="layui-btn layui-btn-sm" data-modal="<?php echo base_url ( 'admin/royalty_rules/add_gmp' ) ?>"  data-title="提成系数（gmp）" data-width="450px"><i class="layui-icon">&#xe654;</i></button>
            </div>
        </script>

        <script type="text/html" id="barDemo2">
            <a class="layui-btn layui-btn-xs"  lay-event="edit" data-modal="<?php echo base_url ( 'admin/royalty_rules/add_gmp?type=edit' ) ?>" data-title="提成系数（gmp）" data-width="450px">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
</div>


<script type="text/javascript">

var data_px = [],px_id = 0,px_edit_id = 0;
var data_gmp = [],gmp_id = 0,gmp_edit_id = 0;
var table;

layui.use('table', function(){
   table = layui.table;

    layui.use('table', function(){
        var table = layui.table;

        //提成系数px列表
        table.render({
            elem: '#test'
            ,id: 'idTest'
            ,data:data_px
            ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
            ,defaultToolbar: []
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,cols: [[
                {field:'id', width:80, title: 'ID' }
                ,{field:'range_start',  title: '营业额（最小）', minWidth: 150}
                ,{field:'range_end',  title: '营业额（最大）', minWidth: 150}
                ,{field:'ratio', width:80, title: '系数'}
                ,{field:'remarks', title: '备注'} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                ,{field:'right', title:'操作', toolbar: '#barDemo', minWidth: 120}
            ]]
        });

        //提成系数gmp列表
        table.render({
            elem: '#test2'
            ,id: 'idTest2'
            ,data:data_gmp
            ,toolbar: '#toolbarDemo2' //开启头部工具栏，并为其绑定左侧模板
            ,defaultToolbar: []
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,cols: [[
                {field:'id', width:80, title: 'ID' }
                ,{field:'range_start',  title: '毛利润（最小）', minWidth: 150}
                ,{field:'range_end',  title: '毛利润（最大）', minWidth: 150}
                ,{field:'ratio', width:80, title: '系数'}
                ,{field:'remarks', title: '备注'} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                ,{field:'right', title:'操作', toolbar: '#barDemo2', minWidth: 120}
            ]]
        });
    });

  //监听行工具事件
table.on('tool(test)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
      layer.confirm('确定删除该数据？', function(index){
          var data_tmp = [];
          $.each(data_px,function(i,item){
              if(data.id!=item.id){
                  data_tmp.push(item);
              }
          });
        data_px = data_tmp;
        obj.del();
        layer.close(index);
      });
    } else if(obj.event === 'edit'){
        px_edit_id = data.id;
    }
  });
//监听行工具事件
table.on('tool(test2)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
        layer.confirm('确定删除该数据？', function(index){
            var data_tmp = [];
            $.each(data_gmp,function(i,item){
                if(data.id!=item.id){
                    data_tmp.push(item);
                }
            });
            data_gmp = data_tmp;
            obj.del();
            layer.close(index);
        });
    } else if(obj.event === 'edit'){
        gmp_edit_id = data.id;
    }
});
});

function save_form() {
    var form = $('form'),data = form.serializeArray();

    data.push({"name":"data_px","value":JSON.stringify(data_px)});
    data.push({"name":"data_gmp","value":JSON.stringify(data_gmp)});

    $.post(form.attr('action'), data, function (response) {
        if (!response.status) {
            layer.msg(response.msg, {time: 2000, icon: 6});
            return false;
        } else if(response.status==2){
            layer.msg(response.msg, {time: 2000, icon: 6}, function () {
                window.location.href = '/admin/royalty_rules/edit/'+response.data.id;
            })
        } else {
            layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                window.location.href = '/admin/royalty_rules/index';
            })
        }
    },'json');
}
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>