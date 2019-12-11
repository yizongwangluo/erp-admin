<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">账号申请/变更</li>
        <li><a href='<?php echo base_url ( 'admin/apply/index' ) ?>'>我的申请</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <form action="<?php echo base_url ( 'admin/apply/add' ) ?>" class="layui-form" method="post">
                <input type="hidden" value=""  name="id">
                <input type="hidden" value="<?=$admin['id']?>"  name="user_id">
                <input type="hidden" value="<?=strtotime(date("Y-m-d"))?>"  name="date">
                <div class="layui-form-item">
                    <label class="layui-form-label">申请类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="type" value= 0 title="申请新账号" <?php if ($info['type'] == 0):?>checked="checked"<?php endif; ?> >
                        <input type="radio" name="type" value= 1 title="修改旧账号" <?php if ($info['type'] == 1):?>checked="checked"<?php endif; ?> >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">账号类型</label>
                    <div class="layui-inline col-xs-3">
                        <select name="account_type" lay-filter="status">
                            <option value="">请选择</option>
                            <option value= 0 >店铺</option>
                            <option value= 1 >企业账号</option>
                            <option value= 2 >个人账号</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">申请概要</label>
                    <div class="layui-inline col-xs-4">
                        <textarea placeholder="请输入申请/变更事由等相关信息" class="layui-textarea" name="apply_summary"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-inline col-xs-4">
                        <input name="apply_remark" value="" type="text" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/apply/index' ) ?>" lay-submit lay-filter="post">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

<style>
    input{
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }

</style>