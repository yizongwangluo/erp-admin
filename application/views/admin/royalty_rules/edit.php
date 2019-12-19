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
        <input type="hidden" value="<?=$info['id']?>" name="id">
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*选择部门：</label>
            <div class="layui-inline">
                <select name="o_id" lay-search>
                    <option value="">请选择部门</option>
                    <?php foreach ( $olist as $v ): ?>
                        <option value="<?= $v['id'] ?>" <?=$info['o_id']==$v['id']?'selected':''?> ><?php if ($v['level'] != 1){ echo '|';
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
                <input name="service_charge" lay-verify="required" value="<?=$info['service_charge']?>" type="number" class="layui-input">
            </div>
            <em>
                （百分比）
            </em>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">	*运费(g)：</label>
            <div class="layui-inline">
                <input name="freight" lay-verify="required" value="<?=$info['freight']?>" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*挂号费：</label>
            <div class="layui-inline">
                <input name="register_fee" lay-verify="required" value="<?=$info['register_fee']?>" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*汇率：</label>
            <div class="layui-inline">
                <input name="exchange_rate" lay-verify="required" value="<?=$info['exchange_rate']?>" type="number" class="layui-input">
            </div>
            <em>例：1美元=7.0044人民币，此处应填 7.0044</em>
        </div>

        </div>
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="<?=$info['remarks']?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">类型：</label>
            <div class="layui-inline">
                <input type="radio" name="type" value="1" title="员工" <?=$info['type']==1?'checked':'';?>>
                <input type="radio" name="type" value="2" title="组长"  <?=$info['type']==2?'checked':'';?>>
                <input type="radio" name="type" value="3" title="主管"  <?=$info['type']==3?'checked':'';?>>
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
        <label class="layui-form-label">提成系数（GPM）：</label>
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
                <button type="button" class="layui-btn layui-btn-sm" lay-event="add" ><i class="layui-icon">&#xe654;</i></button>
            </div>
        </script>
        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs"  lay-event="edit" >编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
    <div class="GPM">
        <script type="text/html" id="toolbarDemo2">
            <div class="layui-btn-container">
                <button type="button" class="layui-btn layui-btn-sm"  lay-event="add"><i class="layui-icon">&#xe654;</i></button>
            </div>
        </script>

        <script type="text/html" id="barDemo2">
            <a class="layui-btn layui-btn-xs"  lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
</div>

<script type="text/javascript">
var table;
layui.use('table', function(){
   table = layui.table;
    layui.use('table', function(){
        var table = layui.table;

        //提成系数px列表
        table.render({
            elem: '#test'
            ,id: 'idTest'
            ,url:'/admin/royalty_rules/get_list_px'
            ,where:{r_id: <?=$info['id']?>}
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

        //提成系数GPM列表
        table.render({
            elem: '#test2'
            ,id: 'idTest2'
            ,url:'/admin/royalty_rules/get_list_gpm'
            ,where:{r_id: <?=$info['id']?>}
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

table.on('toolbar(test)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id);
    switch(obj.event) {
        case 'add':
            layer.open({
                type: 2,
                title: '添加 提成系数（PX）',
                area: ['500px','350px'],
                shadeClose: true, //点击遮罩关闭
                maxmin: true,
                content: '/admin/royalty_rules/edit_px',
                success: function(layero, index){
                    layer.getChildFrame('body', index).find('input[name="r_id"]').val(<?=$info['id']?>);
                }
            });
    }
});

table.on('toolbar(test2)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id);
    switch(obj.event) {
        case 'add':
            layer.open({
                type: 2,
                title: '添加 提成系数（GPM）',
                area: ['500px','350px'],
                shadeClose: true, //点击遮罩关闭
                maxmin: true,
                content: '/admin/royalty_rules/edit_gpm',
                success: function(layero, index){
                    layer.getChildFrame('body', index).find('input[name="r_id"]').val(<?=$info['id']?>);
                }
            });
    }
});

  //监听行工具事件
table.on('tool(test)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
      layer.confirm('确定删除该数据？', function(index){
          $.post('/admin/royalty_rules/del_px',{id:data.id},function(e){
              if(!e.status){
                  layer.msg(e.msg, {time: 2000, icon: 6});
                  return false;
              }
          });
          obj.del();
          layer.close(index);
      });
    } else if(obj.event === 'edit'){
        layer.open({
            type: 2,
            title: '修改 提成系数（PX）',
            area: ['500px','350px'],
            shadeClose: true, //点击遮罩关闭
            maxmin: true,
            content: '/admin/Royalty_rules/edit_px',
            success: function(layero, index){
                var body = layer.getChildFrame('body', index);
                console.log(body);
                $.each(data,function(k,v){
                    body.find('input[name="'+k+'"]').val(v);
                })
                body.find('input[name="r_id"]').val(<?=$info['id']?>);
            }
        });
    }
  });
//监听行工具事件
table.on('tool(test2)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
        layer.confirm('确定删除该数据？', function(index){
            $.post('/admin/royalty_rules/del_gpm',{id:data.id},function(e){
                if(!e.status){
                    layer.msg(e.msg, {time: 2000, icon: 6});
                    return false;
                }
            });
            obj.del();
            layer.close(index);
        });
    } else if(obj.event === 'edit'){
        layer.open({
            type: 2,
            title: '修改 提成系数（GPM）',
            area: ['500px','350px'],
            shadeClose: true, //点击遮罩关闭
            maxmin: true,
            content: '/admin/Royalty_rules/edit_gpm',
            success: function(layero, index){
                var body = layer.getChildFrame('body', index);
                console.log(body);
                $.each(data,function(k,v){
                    body.find('input[name="'+k+'"]').val(v);
                })
                body.find('input[name="r_id"]').val(<?=$info['id']?>);
            }
        });
    }
});
});

function save_form() {
    var form = $('form'),data = form.serializeArray();

    $.post(form.attr('action'), data, function (response) {
        if (!response.status) {
            layer.msg(response.msg, {time: 2000, icon: 6});
            return false;
        }else {
            layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                window.location.href = '<?php echo site_url ( 'admin/royalty_rules/index' ); ?>';
            })
        }
    },'json');
}
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>