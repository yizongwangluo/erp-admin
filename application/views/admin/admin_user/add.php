<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?php echo base_url ( 'admin/admin_user/lists' ) ?>">账户列表</a></li>
        <li class="layui-this">新增账户</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/admin_user/add' ) ?>" method="post">
                <input type="hidden" value="<?=$user_info['id']?>" name="user_id">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="user_name" value="<?=$user_info['user_name']?>" required lay-verify="required" placeholder="请输入用户名"  class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" value="" placeholder="请输入密码" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">工号</label>
                        <div class="layui-input-inline">
                            <input type="text" name="job_number" value="<?=$user_info['job_number']?>" required lay-verify="required" placeholder="请输入工号" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">真实姓名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="real_name" value="<?=$user_info['real_name']?>" required lay-verify="required" placeholder="请输入真实姓名" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">生日</label>
                        <div class="layui-input-inline">
                            <input name="birthday" value="<?= $user_info['birthday']?>"  placeholder="出生日期" class="layui-input date-time">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">身份证</label>
                        <div class="layui-input-inline">
                            <input type="text" name="id_card_no" value="<?=$user_info['id_card_no']?>" placeholder="请输入身份证号码" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">电话号码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="phone" value="<?=$user_info['phone']?>" placeholder="请输入电话号码" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">户口所在地</label>
                        <div class="layui-input-inline">
                            <input type="text" name="region" value="<?=$user_info['region']?>" placeholder="请输入户口所在地" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">学历</label>
                        <div class="layui-input-inline">
                            <input type="text" name="education" value="<?=$user_info['education']?>" placeholder="请输入文化程度" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">银行账号</label>
                        <div class="layui-input-inline">
                            <input type="text" name="bank_account" value="<?=$user_info['bank_account']?>" placeholder="请输入银行账号" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">开户银行</label>
                        <div class="layui-input-inline">
                            <input type="text" name="account_bank" value="<?=$user_info['account_bank']?>"  placeholder="请输入开户银行" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">入职时间</label>
                        <div class="layui-input-inline">
                            <input name="entry_time" value="<?= $user_info['entry_time'] ? date('Y-m-d',$user_info['entry_time']) : '' ?>" required lay-verify="required" placeholder="入职时间" class="layui-input date-time">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">转正时间</label>
                        <div class="layui-input-inline">
                            <input name="turn_time" value="<?= $user_info['turn_time'] ? date('Y-m-d',$user_info['turn_time']) : '' ?>" placeholder="转正时间" class="layui-input date-time">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">试用期限</label>
                        <div class="layui-inline">
                                <input type="text" name="trial_period" value="<?=$user_info['trial_period']?>" placeholder="请输入试用期限" class="layui-input">
                        </div>
                        <em>(单位：月)</em>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">试用期薪资</label>
                        <div class="layui-input-inline">
                            <input type="text" name="probationary_salary" value="<?=$user_info['probationary_salary']?>" placeholder="请输入试用期薪资" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">转正薪资</label>
                        <div class="layui-input-inline">
                            <input type="text" name="positive_salary" value="<?=$user_info['positive_salary']?>"  placeholder="请输入转正薪资" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-inline">
                            <input type="text" name="remark" value="<?=$user_info['remark']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">基本工资</label>
                        <div class="layui-input-inline">
                            <input type="text" name="basic_salary" value="<?=$user_info['basic_salary']?>" placeholder="请输入基本工资" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">岗位工资</label>
                        <div class="layui-input-inline">
                            <input type="text" name="post_salary" value="<?=$user_info['post_salary']?>"  placeholder="请输入岗位工资" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">固定补贴</label>
                        <div class="layui-input-inline">
                            <input type="text" name="fixed_subsidy" value="<?=$user_info['fixed_subsidy']?>"  placeholder="请输入固定补贴" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                        <label class="layui-form-label">所属权限组</label>
                        <div class="layui-input-block col-xs-4" id="tag_ids2">
                        </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">职位</label>
                    <div class="layui-input-block col-xs-4" id="tag_ids1">
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
                        <button class="layui-btn" type="button" onclick="javascript:history.back(-1);">取消</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

<script>
    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //同时绑定多个
        lay('.date-time').each(function () {
            laydate.render({
                elem: this
                , trigger: 'click'
            });
        });
    });
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
            //添加验证
            ,verify:'required'
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
<style>
    em{
        color: red;
        padding-left: 5px;
    }
</style>
