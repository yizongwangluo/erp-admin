<style>
    .w {
        width: 125px;
    }
</style>
<form method="post" class="layui-form" id="add_gmp">
    <div class="layui-field-box">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label w">*毛利润（最小）：</label>
            <div class="layui-inline">
                <input name="range_start" lay-verify="required" value="0" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label w">*毛利润（最大）：</label>
            <div class="layui-inline">
                <input name="range_end" lay-verify="required" value="" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label w">	系数：</label>
            <div class="layui-inline">
                <input name="ratio" lay-verify="required" value="0" type="number" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label w">备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="" type="text" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" onclick="save_form_gmp()">确定</button>
        </div>
    </div>
    </div>
</form>

<script>
    if(<?=input('type')?1:0?> && gmp_edit_id){ //渲染页面
        $.each(data_gmp,function(i,item){
            if(gmp_edit_id==item.id){
                $.each(item,function(k,v){
                    $("input[name='"+k+"']").val(v);
                });
                return false;
            }
        })
    }
    function save_form_gmp() {
        if(!$('input[name="range_end"]').val()){
            layer.msg('毛利润（最大） 必填', {icon: 5});
            return false;
        }
        var input = $('#add_gmp').serializeArray();
        var values = {};
        var x;
        for(x in input){
            values[input[x].name] = input[x].value;
        }

        if(<?=input('type')?1:0?> && gmp_edit_id){ //修改
            values['id'] = gmp_edit_id;
            $.each(data_gmp,function(i,item){
                if(gmp_edit_id==item.id){
                    data_gmp[i] = values;
                }
            })
        }else{ //添加
            gmp_id++;
            values['id'] = gmp_id;
            data_gmp.push(values);
        }
        table.reload('idTest2',{data:data_gmp}); //重载table
        $('.layui-layer-close').trigger('click');
    }
</script>