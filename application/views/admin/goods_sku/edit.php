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
    <form action="/admin/goods_sku/save" method="post" class="layui-form" id="add_sku">
        <div class="layui-field-box">
            <input type="hidden" name="id" class="layui-input">
            <input type="hidden" name="spu_id" class="layui-input">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">SKU编码：</label>
                    <div class="layui-inline">
                        <input type="text" name="code" value="" class="layui-input" readonly style="background: #e0e0e0;" >
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">别名：</label>
                    <div class="layui-inline">
                        <input type="text" name="alias" value="" class="layui-input">
                    </div>
                    <em>多个别名以 , 隔开</em>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">规格名1：</label>
                    <div class="layui-inline" style="width: 100px;">
                        <input type="text" name="norms_name" value="" class="layui-input" >
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">规格值1：</label>
                    <div class="layui-inline" style="width: 100px;">
                        <input type="text" name="norms" value="" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">规格名2：</label>
                    <div class="layui-inline" style="width: 100px;">
                        <input type="text" name="norms_name1" value="" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">规格值2：</label>
                    <div class="layui-inline" style="width: 100px;">
                        <input type="text" name="norms1" value="" class="layui-input">
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
                <div class="layui-inline layui-hide">
                    <label class="layui-form-label">采购链接：</label>
                    <div class="layui-inline">
                        <input name="source_address" lay-verify="required" value="" type="text" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">备注：</label>
                    <div class="layui-inline">
                        <input name="remarks" lay-verify="required" value="" type="text" class="layui-input">
                    </div>
                </div>
                <div id="gys">
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
                </div>
                <!--<div class="layui-inline">
                    <label for="" class="layui-form-label">类型</label>
                    <div class="layui-inline sku_type">
                        <input class="type" type="radio" checked value="0" name="type" title="普通sku" lay-filter="type">
                        <input class="type" type="radio" value="1" name="type" title="组合sku" lay-filter="type">
                    </div>
                </div>-->
<!--                <div class="layui-inline">-->
<!--                    <label for="" class="layui-form-label">测试sku</label>-->
<!--                    <div class="layui-inline">-->
<!--                        <input class="is_real" type="radio" checked value="0" name="is_real" title="否" lay-filter="is_real">-->
<!--                        <input class="is_real" type="radio" value="1" name="is_real" title="是" lay-filter="is_real">-->
<!--                    </div>-->
<!--                </div>-->
                <div class="layui-inline">
                    <label for="" class="layui-form-label">同步马帮状态</label>
                    <div class="layui-inline">
                        <input class="is_mabang" type="radio" checked value="0" name="is_mabang" title="未同步" lay-filter="is_mabang">
                        <input class="is_mabang" type="radio" value="1" name="is_mabang" title="已同步" lay-filter="is_mabang">
                    </div>
                </div>
            </div>
            <div class="layui-form-item" id="save_from">
                <div class="layui-input-block">
                    <button class="layui-btn" type="button" onclick="save_form_sku()">确定</button>
                </div>
            </div>
        </div>
    </form>

    <script>

        function save_form_sku() {

            var form = $('#add_sku');
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

            $.post(form.attr('action'), form.serializeArray(), function (response) {
                if (!response.status) {
                    layer.msg(response.msg, {time: 2000, icon: 6});
                } else {
                    layer.msg('保存成功', {time: 2000, icon: 6},function(){
                        window.parent.table.reload('idTest'); //重载 table
                        parent.layer.close(index); //再执行关闭
                    });
                }

            },'json');
        }

        $('.layui-form-item').on('click','.layui-gys-del',function(){
            $(this).parent().empty();
        }).on('click','.layui-gys-add',function(){
            var len = $('.gys').length;
            len++;

            var html = window.parent.gys_html;
            html=html.replace(new RegExp('{{}}',"g"),len);
            html=html.replace(new RegExp('{url}',"g"),'');
            html=html.replace(new RegExp('{name}',"g"),'');

            $(this).parent().after(html);
        });

    </script>
<?php $this->load->view ( 'admin/common/footer' ) ?>