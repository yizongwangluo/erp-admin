<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    em{
        color: red;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li  class="layui-this">分配编码</li>
    </ul>
<form action="<?php echo base_url ( 'admin/goods_apply/save' ) ?>" method="post" class="layui-form">
    <div class="layui-field-box">
    <input type="hidden" name="id" value="<?= $info['id'] ?>">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*SPU编码：</label>
            <div class="layui-inline">
                <input name="code" lay-verify="required" value="<?= $info['code'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*产品名：</label>
            <div class="layui-inline">
                <input name="name" lay-verify="required" value="<?= $info['name'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">关键词：</label>
            <div class="layui-inline">
                <input name="keyword" lay-verify="required" value="<?= $info['keyword'] ?>" type="text" class="layui-input">
                <em>多个关键词以,号隔开</em>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*产品图片：</label>
            <div class="layui-inline">
                <input id="thumb_img" name="img" value="<?=$info['img']?>" type="text" class="layui-input thumb_img" />
            </div>
            <div class="layui-inline">
                <a id="thumb_img_btn"  href="javascript:void(0)" class="layui-btn upload-img-all" >上传图片</a>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">货源地址：</label>
            <div class="layui-inline">
                <input name="source_address" lay-verify="required" value="<?= $info['source_address'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">起批量：</label>
            <div class="layui-inline">
                <input name="batch_quantity" lay-verify="required" value="<?= $info['batch_quantity'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*视频链接：</label>
            <div class="layui-inline">
                <input name="benchmarking" lay-verify="required" value="<?= $info['benchmarking'] ?>" type="text" class="layui-input">
                <em>多个链接以 , 隔开</em>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">投放区域：</label>
            <div class="layui-inline">
                <input name="launch_area" lay-verify="required" value="<?= $info['launch_area'] ?>" type="text" class="layui-input">
                <em>多地区以/号分隔</em>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">支持语言：</label>
            <div class="layui-inline">
                <input name="language" lay-verify="required" value="<?= $info['language'] ?>" type="text" class="layui-input">
                <em>多语言用,号分隔</em>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">运费：</label>
            <div class="layui-inline">
                <input name="freight" lay-verify="required" value="<?= $info['freight'] ?>" type="text" class="layui-input">
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
            <label for="" class="layui-form-label">是否液体</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_liquid']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_liquid" title="是">
                <input checked="<?=$info['is_liquid']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_liquid" title="否">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">*是否粉末</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_powder']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_powder" title="是">
                <input checked="<?=$info['is_powder']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_powder" title="否">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label for="" class="layui-form-label">是否定制</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_customized']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_customized" title="是">
                <input checked="<?=$info['is_customized']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_customized" title="否">
            </div>
        </div>
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
    </div>
        <div class="layui-form-item">
        <div class="layui-inline">
            <label for="" class="layui-form-label">状态</label>
            <div class="layui-inline">
                <select name="status" lay-verify="required">
                    <?php foreach($this->enum_field->get_values('is_status') as $key=>$value){ ?>
                        <option value="<?php echo $key ?>" <?php if($info['status']==$key){ echo 'selected'; } ?> ><?php echo $value ?></option>
                    <?php   } ?>
                </select>
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
            <button class="layui-btn" type="button" onclick="save_form()">确定</button>
            <button class="layui-btn" type="button" onclick="javascript:history.back(-1);">返回</button>
        </div>
    </div>
    </div>
</form>
    <div class="px">
        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs"  lay-event="edit" >编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
</div>

<script type="text/javascript">
    var table,is_status = <?=json_encode($this->enum_field->get_values('is_status'))?>;

    layui.use('table', function(){
        table = layui.table;

        layui.use('table', function(){
            var table = layui.table;

            //sku列表
            table.render({
                elem: '#test'
                ,id: 'idTest'
                ,url:'/admin/goods_sku_apply/sku_list/<?=$info['id']?>'
                ,defaultToolbar: []
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', width:80, title: 'ID' }
                    ,{field:'norms',  title: '规格',minWidth:100}
                    ,{field:'code',  title: 'SKU编码'}
                    ,{field:'img',  title: '图片', templet: function(res){
                        return '<a href="'+res.img+'" target="_blank">查看</a>'
                    }}
                    ,{field:'price', width:80, title: '采购价格'}
                    ,{field:'size', title: '包装尺寸(长*宽*高)'} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                    ,{field:'weight', title: '重量（g）'}
                    ,{field:'cycle', title: '采购周期'}
                    ,{field:'status', title: '状态', templet: function(res){
                        return '<a>'+is_status[res.status]+'</a>'
                    }}
                    ,{field:'right', title:'操作', toolbar: '#barDemo',minWidth:150}
                ]]
            });
        });

        //监听行工具事件
        table.on('tool(test)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('确定删除该数据？', function(index){
                    $.post('/admin/goods_sku_apply/delete',{id:data.id},function(e){
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
                    title: '修改SKU',
                    area: ['500px','500px'],
                    shadeClose: true, //点击遮罩关闭
                    maxmin: true,
                    content: '/admin/goods_apply/edit_sku_distribution',
                    success: function(layero, index){
                        var body = layer.getChildFrame('body', index);
                        $.each(data,function(k,v){
                            if(k=='type' || k=='is_real'){
                                layui.use('form', function() { //监控复选框状态
                                    var form = layui.form;
                                    body.find("input[name='"+k+"'][value='"+v+"']").prop('checked',true);
                                    console.log(k+'：'+v);
                                    form.render();
                                });
                            }else{
                                body.find('input[name="'+k+'"]').val(v);
                            }
                            body.find('input[name="spu_id"]').val(<?=$info['id']?>);
                        });
                    }
                });
            }
        });
    });

    function save_form() {
        var form = $('form'),index = layer.load(),data = form.serializeArray();

        $.post(form.attr('action'), data , function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                layer.close(index);
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location.href = '<?php echo site_url ( 'admin/goods_apply/distribution' ); ?>';
                })
            }
        }, 'json');
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>