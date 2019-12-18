<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/datareview/add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <input type="hidden" value="<?=$admin['id']?>"  name="reviewer">
    <input type="hidden" value="<?=time()?>"  name="review_time">
    <input type="hidden" value="<?=date('Y-m-d',$info['date'])?>"  name="date">
    <input type="hidden" value="<?=$info['shop_id']?>"  name="shop_id">
    <input type="hidden" value="<?=$info['paid_orders']?>"  name="paid_orders">
    <input type="hidden" value="<?=$info['turnover']?>"  name="turnover">
    <input type="hidden" value="<?=$info['sku_total_cost']?>"  name="sku_total_cost">
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">广告费用</label>
        <div class="layui-input-block">
            <input type="text" name="ad_cost" value="<?=$info['ad_cost']?>" required lay-verify="required" placeholder="请输入金额" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">审核状态</label>
        <div class="layui-input-block">
            <input type="radio" name="review_status" value="2" title="已审核" <?php if ($info['review_status'] == 2):?>checked="checked"<?php endif; ?> >
            <input type="radio" name="review_status" value="1" title="未审核" <?php if ($info['review_status'] == 1):?>checked="checked"<?php endif; ?> >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/datareview/'.$url ) ?>" lay-submit lay-filter="post">保存</button>
            <a href='<?php echo base_url ( 'admin/datareview/'.$url ) ?>'><button type="button" class="layui-btn ">取消</button></a>
        </div>
    </div>
</form>
