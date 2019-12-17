<div class="layui-form-item">
    <label class="layui-form-label">企业账户ID：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['company_account_id'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">网站域名：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $domain['domain'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">所属企业主体：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $company['company_name'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">所属人：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $user['real_name'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">是否解限：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['isunlock'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">账户状态：</label>
    <div class="layui-inline">
        <div class="detail">
            <?php
            if($info['status'] == 0){
                echo "正常";
            }else if($info['status'] == 1){
                echo "封户";
            }else{
                echo "申诉中";
            }
            ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">备注：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['companyaccount_remark'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <a href='<?php echo base_url ( 'admin/mycompanyaccount/index' ) ?>'><button type="button" class="layui-btn ">确定</button></a>
    </div>
</div>
<style>
    .layui-form-item{
        margin-bottom: 5px;
    }
    .layui-form-label{
        width: 120px;
    }

    .detail{
        padding: 10px;
    }
</style>