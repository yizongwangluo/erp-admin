<?php $this->load->view ( 'admin/common/header' ) ?>
    <style>
        .w {
            width: 125px;
        }
    </style>
    <form action="/admin/goods_apply/save_sku" method="post" class="layui-form" id="add_sku">
        <div class="layui-field-box">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">规格：</label>
                    <div class="layui-inline">
                        <input type="text" name="norms" value="" class="layui-input">
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
                    <div class="layui-inline">
                        <a id="thumb_img_btn"  href="javascript:void(0)" class="layui-btn upload-img-all" >上传图片</a>
                    </div>
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
                <div class="layui-inline">
                    <label for="" class="layui-form-label">类型</label>
                    <div class="layui-inline sku_type">
                        <input class="type" type="radio" value="0" name="type" title="普通sku" lay-filter="type">
                        <input class="type" type="radio" value="1" name="type" title="组合sku" lay-filter="type">
                    </div>
                </div>
                <div class="layui-inline">
                    <label for="" class="layui-form-label">测试sku</label>
                    <div class="layui-inline">
                        <input class="is_real" type="radio" value="0" name="is_real" title="否" lay-filter="is_real">
                        <input class="is_real" type="radio" value="1" name="is_real" title="是" lay-filter="is_real">
                    </div>
                </div>
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

        if(<?=input('type')?1:0?> && data.sku_edit_id){ //渲染页面
            $.each(data.data_sku,function(i,item){
                if(data.sku_edit_id==item.id){
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

            var values = {},norms = {};
            var x;
            for(x in input){
                values[input[x].name] = input[x].value;
            }

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

            data.table.reload('idTest',{data:data.data_sku}); //重载 table
            parent.layer.close(index); //再执行关闭

        }
    </script>
<?php $this->load->view ( 'admin/common/footer' ) ?>