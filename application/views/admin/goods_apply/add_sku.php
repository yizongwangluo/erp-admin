<?php $this->load->view ( 'admin/common/header' ) ?>
    <style>
        .w {
            width: 125px;
        }
        .layui-inline-duan{
            width: 120px;
        }
        .layui-gys-icon:hover{
            color: #00a0e9;
            cursor: pointer;
        }
    </style>
    <form action="/admin/goods_apply/save_sku"  method="post" class="layui-form" id="add_sku">
        <div class="layui-field-box">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">别名：</label>
                    <div class="layui-inline">
                        <input type="text" name="alias" value="" class="layui-input" id="alias">
                    </div>
                    <em>多个别名以 , 隔开</em>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">规格1：</label>
                    <div class="layui-inline layui-inline-duan">
                        <input type="text" name="norms_name" value="" placeholder="名" class="layui-input" >
                    </div>-
                    <div class="layui-inline layui-inline-duan">
                        <input type="text" name="norms" value="" placeholder="值" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">规格2：</label>
                    <div class="layui-inline  layui-inline-duan" >
                        <input type="text" name="norms_name1" value="" placeholder="名" class="layui-input">
                    </div>-
                    <div class="layui-inline  layui-inline-duan" >
                        <input type="text" name="norms1" value=""   placeholder="值" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">*采购价格：</label>
                    <div class="layui-inline">
                        <input name="price" lay-verify="required" value="0" type="number" class="layui-input">
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
                    <label class="layui-form-label">产品包装尺寸：</label>
                    <div class="layui-inline">
                        <input name="size" lay-verify="required" value="" type="text" class="layui-input">
                    </div>
                    <em>长*宽*高</em>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">含包装的重量(克)：</label>
                    <div class="layui-inline">
                        <input name="weight" lay-verify="required" value="0" type="number" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">采购周期：</label>
                    <div class="layui-inline">
                        <input name="cycle" lay-verify="required" value="0" type="number" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">敏感信息：</label>
                    <div class="layui-inline">
                        <input name="information" lay-verify="required" value="" type="text" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">备注：</label>
                    <div class="layui-inline">
                        <input name="remarks" lay-verify="required" value="" type="text" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline gys">
                    <label class="layui-form-label">供应商：</label>
                    <div class="layui-inline  layui-inline-duan">
                        <input type="text" name="supplier_name_1" value="" placeholder="供应商名称"  class="layui-input">
                    </div>-
                    <div class="layui-inline  layui-inline-duan">
                        <input type="text" name="supplier_url_1" value=""  placeholder="采购地址"  class="layui-input">
                    </div>
                    <i class="layui-icon layui-gys-icon layui-gys-add">&#xe654;</i>
                    <i class="layui-icon layui-gys-icon layui-gys-del">&#xe640;</i>
                </div>
                <!--<div class="layui-inline">
                    <label for="" class="layui-form-label">类型</label>
                    <div class="layui-inline sku_type">
                        <input class="type" type="radio" value="0" name="type" title="普通sku" lay-filter="type">
                        <input class="type" type="radio" value="1" name="type" title="组合sku" lay-filter="type">
                    </div>
                </div>-->
<!--                <div class="layui-inline">-->
<!--                    <label for="" class="layui-form-label">测试sku</label>-->
<!--                    <div class="layui-inline">-->
<!--                        <input class="is_real" type="radio" value="0" name="is_real" title="否" lay-filter="is_real">-->
<!--                        <input class="is_real" type="radio" value="1" name="is_real" title="是" lay-filter="is_real">-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" type="button" onclick="save_form_sku()">确定</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        var data = window.parent;

        var gys_html = '<div class="layui-inline gys">' +
            '<label class="layui-form-label">供应商：</label>' +
            '<div class="layui-inline  layui-inline-duan">' +
            '<input type="text" name="supplier_name_{{}}" value="" placeholder="供应商名称"  class="layui-input">' +
            '</div>- ' +
            '<div class="layui-inline  layui-inline-duan">' +
            '<input type="text" name="supplier_url_{{}}" value=""  placeholder="采购地址"  class="layui-input">' +
            '</div>' +
            '<i class="layui-icon layui-gys-icon layui-gys-add">&#xe654;</i>' +
            '<i class="layui-icon layui-gys-icon layui-gys-del">&#xe640;</i>' +
            '</div>';


        if(<?=input('type')?1:0?> && data.sku_edit_id){ //渲染页面
            $.each(data.data_sku,function(i,item){
                if(data.sku_edit_id==item.id){

                    //获取供应商信息个数
                    var gys_len = 2;
                    $.each(item,function(k,v){
                        var re = new RegExp('supplier_name');
                        if(re.test(k) && k!='supplier_name_1'){
                            var html = gys_html;
                            var AllReplace = new RegExp('{{}}',"g");
                            html=html.replace(AllReplace,gys_len);
                            $('.gys:last').after(html);
                            gys_len++;
                        }
                    });

                    $.each(item,function(k,v){
                        if(k=='type' || k=='is_real'){
                            layui.use('form', function() { //监控复选框状态
                                var form = layui.form;
                                $("input[name='"+k+"'][value='"+v+"']").prop('checked',true);
                                console.log(k+'：'+v);
                                form.render();
                            });
                        }else{
                            $("input[name='"+k+"']").val(v);
                        }
                    });
                    return false;
                }
            })
        }

        function save_form_sku() {

            var input = $('#add_sku').serializeArray();
            var re = new RegExp('supplier_name');

            var values = {},norms = {},aliasarr = [],dataass =[];
            var x;
            for(x in input){
                var t = Number(x)+1;
                if(re.test(input[x].name) && ((input[x].value!='' && input[t].value=='') || (input[x].value=='' && input[t].value!=''))){
                    layer.msg('供应商信息未填写完整！', {time: 2000, icon: 5});return;
                }
                values[input[x].name] = input[x].value;
            }

            if(!values.norms_name){
                layer.msg('规格名1必填', {time: 2000, icon: 5});return;
            }

            if(!values.norms){
                layer.msg('规格值1必填', {time: 2000, icon: 5});return;
            }

            if(!values.img){
                layer.msg('请上传产品图片', {time: 2000, icon: 5});return;
            }

            //别名判断
            if(values.alias){

                data.data_sku.forEach(function( val, index ) {
                    if(val.alias && val.id!=data.sku_edit_id){
                        aliasarr = aliasarr.concat(val.alias.split(','));
                    }
                });

                if(aliasarr.length>0){
                    var alias_tmp = values.alias.split(',');
                    var ar = aliasarr.filter(function(n) {
                        return alias_tmp.indexOf(n) != -1
                    });

                    if(ar.length>0){
                        layer.msg('别名重复！', {time: 2000, icon: 5});return;
                    }
                }
            }

            $.post('/admin/goods_apply/alias',{alias: values.alias},function(obj){
                if (obj.status != 1) {
                    layer.msg(obj.msg, {time: 2000, icon: 5});return;
                }else{

                    if(<?=input('type')?1:0?> && data.sku_edit_id){ //修改
                        values['id'] = data.sku_edit_id;
                        $.each(data.data_sku,function(i,item){
                            if(data.sku_edit_id==item.id){
                                data.data_sku[i] = values;
                            }
                        })
                    }else{ //添加
                        data.sku_id++;
                        values['id'] = data.sku_id;
                        data.data_sku.push(values);
                    }

                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    console.log(JSON.stringify(data.data_sku));
                    data.table.reload('idTest',{data:data.data_sku}); //重载 table
                    parent.layer.close(index); //再执行关闭

                }
            },'json');
        }

        $('.layui-form-item').on('click','.layui-gys-del',function(){
            $(this).parent().empty();
        }).on('click','.layui-gys-add',function(){
            var len = $('.gys').length;
                len++;

            var html = gys_html;
            var AllReplace = new RegExp('{{}}',"g");
            html=html.replace(AllReplace,len);

            $(this).parent().after(html);
        });

    </script>
<?php $this->load->view ( 'admin/common/footer' ) ?>