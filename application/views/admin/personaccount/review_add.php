<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/personaccount/review_add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <input type="hidden" value="<?=$info['apply_summary']?>"  name="apply_summary">
    <input type="hidden" value="<?=$info['account_type']?>"  name="account_type">
    <input type="hidden" value="<?=$admin['id']?>"  name="reviewer">
    <input type="hidden" value="<?=time()?>"  name="review_time">
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">批注</label>
        <div class="layui-inline col-xs-6">
            <textarea placeholder="请输入驳回原因/申请新账号信息等" class="layui-textarea" name="annotate" required lay-verify="required"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批状态</label>
        <div class="layui-input-block">
            <input type="radio" name="apply_status" value="2" title="审批通过" <?php if ($info['apply_status'] == 2):?>checked="checked"<?php endif; ?> >
            <input type="radio" name="apply_status" value="1" title="驳回" <?php if ($info['apply_status'] == 1):?>checked="checked"<?php endif; ?> >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/personaccount/'.$url ) ?>" lay-submit lay-filter="post">保存</button>
            <a href='<?php echo base_url ( 'admin/personaccount/'.$url ) ?>'><button type="button" class="layui-btn ">取消</button></a>
        </div>
    </div>
</form>
