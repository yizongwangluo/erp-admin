<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
    #daochu{
        cursor:pointer;
        border: 1px solid silver
    }
    .tb_mabang{
        cursor:pointer;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">商品列表</li>
        <li><a href="<?php echo base_url ( 'admin/goods/addexcel' ) ?>">导入</a></li>
    </ul>
    <div class="layui-tab-content">
      <form action="?" method="get" >
                <div class="layui-form">
                    <div class="layui-inline">
                        <select name="category_id" lay-verify="required" lay-search>
                            <option value="">请选择类别</option>
                            <?php foreach($category_list as $key=>$value){ ?>
                                <option value="<?php echo $value['id'] ?>" <?php if($value['id']==$where['category_id']){ echo 'selected'; } ?> <?=$value['status']==2?'disabled':''?> ><?php echo $value['name'] ?></option>
                            <?php   } ?>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <select name="a" lay-verify="required" lay-search>
                            <option value=""  >-- 搜索条件 --</option>
                            <option value="name"  <?=$where['a']=='name'?'selected':'';?>>商品名称</option>
                            <option value="code" <?=$where['a']=='code'?'selected':'';?>>商品编码</option>
                            <option value="sku_code" <?=$where['a']=='sku_code'?'selected':'';?>>sku编码</option>
                            <option value="sku_code_mh" <?=$where['a']=='sku_code_mh'?'selected':'';?>>sku编码模糊搜索</option>
                            <option value="sku_alias" <?=$where['a']=='sku_alias'?'selected':'';?>>sku别名</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input type="text" name="name" value="<?=$where['name']?>"
                               class="layui-input" placeholder="输入搜索条件"/>
                    </div>
                    <div class="layui-inline">
                        <input type="text" class="layui-input" name="datetime" value="<?=$where['datetime']?>" id="test10" placeholder="选择时间">
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    <button class="layui-btn layui-btn-normal" type="button" id="daochu_all">导出</button>
                </div>
            </form>
        <div style='overflow:auto'>
            <table class="layui-table"  style='white-space: nowrap'>
              <thead>
                <tr>
                    <td><input type="checkbox" id="all"><i class="layui-icon layui-icon-print" id="daochu" title="导出"></i> - <i class="layui-icon layui-icon-print" id="daochu_mb" title="导出马帮"></i></td>
                    <td>ID</td>
                    <td class="img">产品图片</td>
                    <td>产品名</td>
                    <td>SKU</td>
                    <td>SKU别名</td>
                    <td>规格名1</td>
                    <td>规格值1</td>
                    <td>规格名2</td>
                    <td>规格值2</td>
                    <td>采购价（元）</td>
                    <td>重量（克）</td>
                    <td>用户名</td>
                    <td>同步（马帮）</td>
<!--                    <td>同步（通途）</td>-->
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><input type="checkbox" class="id" name="ids" value="<?= $v['id'] ?>"/></td>
                      <td><?=$v['id']?></td>
                      <td class="img"><a href="<?=$v['img']?>" target="_blank"><img src="<?=$v['img']?>"></a></td>
                      <td><?=$v['name']?></td>
                      <td><?=$v['sku_code']?></td>
                      <td><?=$v['alias']?></td>
                      <td><?=$v['norms_name']?></td>
                      <td><?=$v['norms']?></td>
                      <td><?=$v['norms_name1']?></td>
                      <td><?=$v['norms1']?></td>
                      <td><?=$v['price']?></td>
                      <td><?=$v['weight']?></td>
                      <td><?=$v['user_name']?></td>
                      <td><?=$v['is_mabang']?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods/info/{$v['id']}"); ?>">查看</a>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods/edit/{$v['id']}"); ?>">编辑</a>
                          <button data-url="<?php echo base_url ( 'admin/goods/add_sku_mabang' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs confirm_post layui-btn-warm">同步到马帮</button>
<!--                          <button data-url="--><?php //echo base_url ( 'admin/goods/add_sku_tongtu' ) ?><!--" data-id="--><?//= $v['id'] ?><!--" class="layui-btn layui-btn-xs confirm_post layui-btn-warm">同步到通途</button>-->
                          <button data-url="<?php echo base_url ( 'admin/goods/delete' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>
                      </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            </div>
        <div class="admin-page">
            <?php echo $page_html; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        //日期时间范围
        laydate.render({
            elem: '#test10'
            ,type: 'datetime'
            ,range: true
        });
    });

        //全选操作
    $('#all').click(function(){
        if($(this).is(':checked')){ //全选
            $('.id').prop('checked',true);
        }else{ //取消全选
            $('.id').prop('checked',false);
        }
    })
    //全选操作end

    $('#daochu_all').click(function(){
        var index = layer.load();
        var t = $('form').serializeArray(),text = '';
        $.each(t, function() {
            text += this.name+'='+this.value+'&';
        });
        $(this).addClass('layui-btn-disabled').attr('disabled','');
        window.location.href = "/admin/goods/daochu_all?"+text;
//        window.location.href = "/admin/goods/daochu_all";
        layer.close(index);
    });

    $('.tb_mabang').click(function(){
        var id  = $(this).attr('data_id'),val = $(this).html();

        if(val=='已同步'){
            val ='更新';
        }else
        { val ='上传'; }

        layer.confirm('确定<a style="color: red">'+val+'</a>该 sku 到马帮吗？', {
            btn: ['确认', '取消'] //可以无限个按钮
        }, function(index, layero){

            var t = layer.load();

            $.post('/admin/goods/add_sku_mabang_one', {sku_id:id} , function (response) {

                layer.close(t);

                if (!response.status) {
                    layer.msg(response.msg, {time: 2000, icon: 6});
                    return false;
                } else {
                    layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                        window.location.href = '<?php echo site_url ( 'admin/goods/index' ); ?>';
                    })
                }
            }, 'json');

        });


    });

    $('#daochu').click(function(){
        var text="";
        $("input[name=ids]").each(function() {
            if ($(this).is(':checked')) {
                text += ","+$(this).val();
            }
        });

        if(text){
            window.location.href = "/admin/goods/daochu?ids="+text;
        }else{
            layer.msg('请选择商品！', {time: 2000, icon: 5});
        }
    });

    $('#daochu_mb').click(function(){
        var text="";
        $("input[name=ids]").each(function() {
            if ($(this).is(':checked')) {
                text += ","+$(this).val();
            }
        });

        if(text){
            window.location.href = "/admin/goods/daochu_mb?ids="+text;
        }else{
            layer.msg('请选择商品！', {time: 2000, icon: 5});
        }
    })

</script>

<?php $this->load->view ( 'admin/common/footer' ) ?>
