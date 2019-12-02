<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/admin_user/add' ) ?>" method="post">
    <input type="hidden" value="<?=$user_info['id']?>" name="user_id">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="user_name" value="<?=$user_info['user_name']?>" required lay-verify="required" placeholder="请输入用户名"  class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" name="password" value="" placeholder="请输入密码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-block">
            <input type="text" name="real_name" value="<?=$user_info['real_name']?>" required lay-verify="required" placeholder="请输入真实姓名" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属权限组</label>
        <div class="layui-input-block" id="tag_ids2">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">职位</label>
        <div class="layui-input-block" id="tag_ids1">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="启用" <?php if (intval ($user_info['is_disable']) == 0 ): ?> checked="checked" <?php endif; ?> >
            <input type="radio" name="status" value="1" title="禁用" <?php if ($user_info['is_disable'] ==1 && is_numeric ($user_info['is_disable'])): ?> checked="checked" <?php endif; ?> >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" data-url="<?= site_url ( 'admin/admin_user/lists' ) ?>" lay-submit lay-filter="post">保存
            </button>
        </div>
    </div>
</form>


<script>

    layui.extend({
        selectM: '/static/common/layui/layui_extends/selectM',
    }).use(['layer','form','jquery','selectM'],function(){
        $ = layui.jquery;
        var form = layui.form
            ,selectM = layui.selectM;

        //多选标签-所有配置
        var tagIns2 = selectM({
            //元素容器【必填】
            elem: '#tag_ids2'
            //候选数据【必填】
            ,data: <?=json_encode($auth_group_list);?>
            //input的name 不设置与选择器相同(去#.)
            ,name: 'role_id'
            //值的分隔符
            ,delimiter: ','
            //默认选中
            ,selected:[<?=$user_info['role_id']?>]
            //候选项数据的键名
            ,field: {idName:'id',titleName:'name'}
        });


        //多选标签-基本配置
        var tagIns1 = selectM({
            //元素容器【必填】
            elem: '#tag_ids1'
            //候选数据【必填】
            ,data: <?=json_encode($org_list);?>
            ,name: 'org_id'
            //添加验证
            ,verify:'required'
            //默认选中
            ,selected:[<?=$user_info['org_id']?>]
        });

        //多选标签-所有配置
//        var tagIns1 = selectM({
//            //元素容器【必填】
//            elem: '#tag_ids1'
//            //候选数据【必填】
//            ,data: <?//=json_encode($org_list);?>
//            //input的name 不设置与选择器相同(去#.)
//            ,name: 'role_id'
//            //值的分隔符
//            ,delimiter: ','
//            //默认选中
//            ,selected:[<?//=$user_info['org_id']?>//]
//            //候选项数据的键名
//            ,field: {idName:'id',titleName:'name'}
//        });
    });

</script>
