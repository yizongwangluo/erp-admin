<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    em{
        color: red;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?=base_url('admin/goods_apply/index')?>">申请列表</a></li>
        <li  class="layui-this">申请</li>
    </ul>
<form action="<?php echo base_url ( 'admin/goods_apply/save' ) ?>" method="post" class="layui-form">
    <div class="layui-field-box">
    <input type="hidden" name="id" value="<?= $info['id'] ?>">
    <div class="layui-form-item">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">采购链接：</label>
            <div class="layui-inline">
                <input name="source_address" id="source_address" lay-verify="required" value="<?= $info['source_address'] ?>" type="text" class="layui-input">
            </div>
            <button type="button" class="layui-btn layui-btn-danger" id="huoqu">获取</button>
        </div>

    </div>
        <!--<div class="layui-inline">
            <label class="layui-form-label">*SPU编码：</label>
            <div class="layui-inline">
                <input name="code" lay-verify="required" value="<?/*= $info['code'] */?>" type="text" class="layui-input">
            </div>
        </div>-->
        <div class="layui-inline">
            <label class="layui-form-label">*产品名：</label>
            <div class="layui-inline">
                <input name="name" lay-verify="required" value="<?= $info['name'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*产品英文名：</label>
            <div class="layui-inline">
                <input name="name_en" lay-verify="required" value="<?= $info['name_en'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*中文报关名：</label>
            <div class="layui-inline">
                <input name="dc_name" lay-verify="required" value="<?= $info['dc_name'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*英文报关名：</label>
            <div class="layui-inline">
                <input name="dc_name_en" lay-verify="required" value="<?= $info['dc_name_en'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">供应商名称：</label>
            <div class="layui-inline">
                <input name="supplier_name" lay-verify="required" value="<?= $info['supplier_name'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">类别：</label>
            <div class="layui-inline">
                <select name="category_id" lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach($category_list as $key=>$value){ ?>
                        <option value="<?php echo $value['id'] ?>" <?=$value['status']==2?'disabled':'';?> ><?php echo $value['name'] ?></option>
                    <?php   } ?>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*产品图片：</label>
            <div class="layui-inline">
                <input id="thumb_img" name="img" value="" type="text" class="layui-input thumb_img" />
            </div>
            <em onclick="javascript:window.open('_blank').location=$('#thumb_img').val()" class="layui-btn layui-btn-xs">预览</em>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">起批量：</label>
            <div class="layui-inline">
                <input name="batch_quantity" lay-verify="required" value="<?= $info['batch_quantity'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">运费：</label>
            <div class="layui-inline">
                <input name="freight" lay-verify="required" value="<?= $info['freight'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">视频链接：</label>
            <div class="layui-inline">
                <input name="benchmarking" lay-verify="required" value="<?= $info['benchmarking'] ?>" type="text" class="layui-input">
                <em>多个链接以 , 隔开</em>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">支持语言：</label>
            <div class="layui-inline">
                <input name="language" lay-verify="required" value="<?= $info['language'] ?>" type="text" class="layui-input">
                <em>多语言用,号分隔</em>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">特性标签：</label>
            <div class="layui-inline">
                <select name="poperty_label" lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach($this->enum_field->get_values('poperty_label') as $value){ ?>
                        <option value="<?php echo $value ?>" <?=$value==$info['poperty_label']?'selected':''?> ><?php echo $value ?></option>
                    <?php   } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">插头类型：</label>
            <div class="layui-inline">
                <input name="plug_type" lay-verify="required" value="<?= $info['plug_type'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">电压：</label>
            <div class="layui-inline">
                <input name="voltage" lay-verify="required" value="<?= $info['voltage'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="<?= $info['remarks'] ?>" type="text" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label for="" class="layui-form-label">是否仿冒</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_imitation']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_imitation" title="是">
                <input checked="<?=$info['is_imitation']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_imitation" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">是否带电池</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_battery']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_battery" title="是">
                <input checked="<?=$info['is_battery']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_battery" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">*是否侵权</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_tort']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_tort" title="是">
                <input checked="<?=$info['is_tort']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_tort" title="否">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label for="" class="layui-form-label">是否带磁</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_magnetism']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_magnetism" title="是">
                <input checked="<?=$info['is_magnetism']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_magnetism" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">*是否粉末</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_powder']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_powder" title="是">
                <input checked="<?=$info['is_powder']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_powder" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">是否定制</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_customized']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_customized" title="是">
                <input checked="<?=$info['is_customized']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_customized" title="否">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label for="" class="layui-form-label">是否有独立包装</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_pack']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_pack" title="是">
                <input checked="<?=$info['is_pack']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_pack" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">*是否有货</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_goods']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_goods" title="是">
                <input checked="<?=$info['is_goods']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_goods" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">液体</label>
            <div class="layui-inline">
                <input  <?=$info['is_liquid']==0 || empty($info['is_liquid']) ? 'checked':'';?> class="checkbox" type="radio" value="0" name="is_liquid" title="非液体">
                <input <?=$info['is_liquid']==1?'checked':'';?> class="checkbox" type="radio" value="1" name="is_liquid" title="液体(化妆品)">
                <input <?=$info['is_liquid']==2?'checked':'';?> class="checkbox" type="radio" value="2" name="is_liquid" title="非液体(化妆品)">
                <input <?=$info['is_liquid']==3?'checked':'';?> class="checkbox" type="radio" value="3" name="is_liquid" title="液体(非化妆品)">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">SKU列表：</label>
        <div class="layui-inline">
          <table class="layui-hide" id="test" lay-filter="test" ></table>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn layui-btn-normal" type="button" onclick="save_form(0)">暂存</button>
            <button class="layui-btn" type="button" onclick="save_form(2)">提交审核</button>
        </div>
    </div>
    </div>
</form>
    <div class="px">
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button type="button" class="layui-btn layui-btn-sm" lay-event="add" ><i class="layui-icon">&#xe654;</i></button>
                <button type="button" class="layui-btn layui-btn-sm  layui-btn-danger" lay-event="del" ><i class="layui-icon">&#xe640;</i></button>
            </div>
        </script>
        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs"  lay-event="edit" >编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
</div>

<script type="text/javascript">
    var data_sku = [],sku_id = 0,sku_edit_id = 0;
    var table;

    layui.use('table', function(){
        table = layui.table;

        layui.use('table', function(){
            var table = layui.table;

            //sku列表
            table.render({
                elem: '#test'
                ,id: 'idTest'
                ,data:data_sku
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: []
                ,cellMaxHeight:10
                ,limit:9999
                ,cellMinWidth: 50 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    ,{field:'id', width:80, title: 'ID' }
                    ,{field:'alias', title: '别名' }
                    ,{field:'norms_name',  title: '规格名1',minWidth:30}
                    ,{field:'norms',  title: '规格值1',minWidth:30}
                    ,{field:'norms_name1',  title: '规格名2',minWidth:30}
                    ,{field:'norms1',  title: '规格值2',minWidth:30}
                    ,{field:'img',  title: '图片', templet: function(res){
                        return '<a href="'+res.img+'" target="_blank"><img width="50px" src="'+res.img+'"></a>'
                    }}
                    ,{field:'price', width:80, title: '采购价格'}
                    ,{field:'size', title: '包装尺寸(长*宽*高)'} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                    ,{field:'weight', title: '重量（g）'}
                    ,{field:'cycle', title: '采购周期'}
                    ,{field:'right', title:'操作', toolbar: '#barDemo',minWidth:150}
                ]]
            });
        });

        table.on('toolbar(test)', function(obj){

            switch(obj.event) {
                case 'add':
                    layer.open({
                        type: 2,
                        title: '添加SKU',
                        area: ['500px','500px'],
                        shadeClose: true, //点击遮罩关闭
                        maxmin: true,
                        content: '/admin/goods_apply/add_sku',
                        success: function(layero, index){
                            layer.getChildFrame('body', index).find('input[name="r_id"]').val(<?=$info['id']?>);
                        }
                    });
                    break;
                case 'del':
                    var checkStatus = table.checkStatus('idTest'),
                        tableData = checkStatus.data,
                        selectCount = tableData.length,
                        delList = [],
                        data_tmp = [];

                    if(selectCount == 0){
                        layer.msg('批量删除至少选中一项数据');
                        return false;
                    }

                    layer.confirm('确定删除<a style="color: red;margin: 5px"> '+selectCount+' </a>项数据吗？', function(index){
                        tableData.forEach(function(n,i){
                             delList.push(n.id);
                        });

                        console.log('delList：'+delList);

                        data_sku.forEach(function(n,i){
                            if(delList.indexOf(n.id)<=-1){
                                data_tmp.push(n);
                            }
                        });

                        data_sku = data_tmp;

                        table.reload('idTest',{data:data_sku}); //重载 table

                        layer.close(index);
                    });
                    break;
            }
        });

        //监听行工具事件
        table.on('tool(test)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('确定删除该数据？', function(index){
                    var data_tmp = [];
                    $.each(data_sku,function(i,item){
                        if(data.id!=item.id){
                            data_tmp.push(item);
                        }
                    });
                    data_sku = data_tmp;
                    obj.del();
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                sku_edit_id = data.id;

                layer.open({
                    type: 2,
                    title: '修改SKU',
                    area: ['500px','500px'],
                    shadeClose: true, //点击遮罩关闭
                    maxmin: true,
                    content: '/admin/goods_apply/add_sku?type=1',
                    success: function(layero, index){
                    }
                });
            }
        });


        $('#huoqu').click(function(){
            var source_address = $('#source_address').val(),index = layer.load();

            if(source_address==''){
                layer.msg('请填写采购链接', {time: 2000, icon: 2});
                layer.close(index);
                return false;
            }else{

                $.post('/admin/goods_apply/source_address_html',{source_address:source_address},function(obj){
                    layer.close(index);
                    console.log(obj.data);
                    if (!obj.status) {
                        layer.msg(obj.msg, {time: 2000, icon: 2});
                    } else {
//                        $('input[name="name"]').val(obj.data.title);
//                        $('input[name="dc_name"]').val(obj.data.title);
                        $('input[name="supplier_name"]').val(obj.data.gys);
                        $('#thumb_img').val(obj.data.img);
                        data_sku = obj.data.sku?obj.data.sku:[];
                        sku_id = obj.data.sku_id?obj.data.sku_id:0;

                        table.reload('idTest',{data:data_sku}); //重载 table
                    }
                },'json')

            }
        })

    });

    function save_form(status) {
        var form = $('form'),index = layer.load(),data = form.serializeArray();

        data.push({"name":"data_sku","value":JSON.stringify(data_sku)});
        data.push({"name":"status","value":status}); //审核状态

        $.post(form.attr('action'), data , function (response) {

            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location.href = '<?php echo site_url ( 'admin/goods_apply/index' ); ?>';
                })
            }
        }, 'json');
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>