<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/accountapproval/add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <input type="hidden" value="<?=$info['apply_summary']?>"  name="apply_summary">
    <input type="hidden" value="<?=$info['account_type']?>"  name="account_type">
    <input type="hidden" value="<?=$admin['id']?>"  name="reviewer">
    <input type="hidden" value="<?=time()?>"  name="review_time">
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">批注</label>
        <div class="layui-inline col-xs-7">
            <textarea placeholder="请输入驳回原因/新账号信息/旧账号更改信息等" class="layui-textarea" name="annotate" required lay-verify="required"><?=$info['annotate']?></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批状态</label>
        <div class="layui-input-block">
            <input type="radio" name="apply_status" value="0" title="待审批" <?php if ($info['apply_status'] == 0):?>checked="checked"<?php endif; ?> >
            <input type="radio" name="apply_status" value="2" title="通过" <?php if ($info['apply_status'] == 2):?>checked="checked"<?php endif; ?> >
            <input type="radio" name="apply_status" value="1" title="驳回" <?php if ($info['apply_status'] == 1):?>checked="checked"<?php endif; ?> >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/accountapproval/'.$url ) ?>" lay-submit lay-filter="post">保存</button>
            <a href='<?php echo base_url ( 'admin/accountapproval/'.$url ) ?>'><button type="button" class="layui-btn ">取消</button></a>
        </div>
    </div>
</form>
