<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .em-red{
        color: red;
        padding-right: 5px;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class=""><a href='<?php echo base_url ( 'admin/advert/index' ) ?>'>申请列表</a></li>
        <li class="layui-this">审核申请</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <form action="<?=base_url ( 'admin/advert/examine' ) ?>" class="layui-form" method="post">
                <input type="hidden" value="<?=$info['id']?>"  name="id">
                <div class="layui-form-item">
                    <label class="layui-form-label"><em class="em-red">*</em>账户类型：</label>
                    <div class="layui-inline">
                        <select name="type" lay-verify="required" >
                            <option value="" >--账户类型--</option>
                            <?php foreach($this->enum_field->get_values('advert_type') as $key=>$value){ ?>
                                <option value="<?=$key?>" <?php if ( $info['type'] == $key ){echo "selected=\"selected\"";}?>><?=$value?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label"><em class="em-red">*</em>广告ID：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="advert_id"  lay-verify="required" value="<?=$info['advert_id']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label"><em class="em-red">*</em>充值金额：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="recharge_amount"  lay-verify="required" value="<?=$info['recharge_amount']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">剩余金额：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="remain_amount" value="<?=$info['remain_amount']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">近两日总花费金额：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total_cost_in_recent_2" value="<?=$info['total_cost_in_recent_2']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">近两日订单量：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="today_2_order_sum" value="<?=$info['today_2_order_sum']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">预计日花费：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="estimated_daily_cost" value="<?=$info['estimated_daily_cost']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">申请原因：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="apply_reason" value="<?=$info['apply_reason']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">申请人：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="u_id" value="<?=$info['user_info']['user_name']?>" placeholder="" disabled class="layui-input">
                        </div>
                    </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><em class="em-red">*</em>账户类型：</label>
                        <div class="layui-inline">
                            <select name="status" lay-verify="required" >
                                <option value="" >--申请状态--</option>
                                <?php foreach($this->enum_field->get_values('advert_status') as $key=>$value){ ?>
                                    <option value="<?=$key?>" <?php if ( $info['status'] == $key && is_numeric($info['status'])){echo "selected=\"selected\"";}?>><?=$value?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">驳回原因：</label>
                        <div class="layui-input-inline">
                            <input type="text" name="reject_reason" value="<?=$info['reject_reason']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item" style="text-align: center;width: 50%;">
                    <div class="layui-inline">
                        <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/advert/index' ) ?>" lay-submit lay-filter="post">保存</button>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn" type="button" onclick="javascript:history.back(-1);">取消</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

<style>
    .layui-form-label{
        width: 100px;
    }

    input{
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }
    em{
        color: red;
    }

</style>
<script>
    layui.use(['layer'], function(){
        var layer = layui.layer;

    });
</script>