<div class="layui-form-item">
        <label class="layui-form-label">网站域名：</label>
        <div class="layui-inline">
            <div class="detail">
                <?= $info['domain'] ?>
            </div>
        </div>
    </div>
<div class="layui-form-item">
    <label class="layui-form-label">网站后台：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['backstage'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">客服邮箱：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['customer_service_email'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">收款paypal：</label>
    <div class="layui-inline">
        <div class="detail">
            <?php
            if(strpos($info['receipt_paypal'],',') !== false) {
                $receipt_paypals = explode(',',$info['receipt_paypal']);
                foreach ($receipt_paypals as $receipt_paypal){
                    echo $receipt_paypal."<br>";
                }
            }else{
                echo $info['receipt_paypal'];
            }
            ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">收款信用卡通道：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['receipt_credit_card'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">扣款方式：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['deduction'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">店铺套餐：</label>
    <div class="layui-inline">
        <div class="detail">
            <?php
            if(strpos($info['shop_package'],',') !== false) {
                $shop_packages = explode(',',$info['shop_package']);
                foreach ($shop_packages as $shop_package){
                    echo $shop_package."<br>";
                }
            }else{
                echo $info['shop_package'];
            }
            ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">是否授权ERP：</label>
    <div class="layui-inline">
        <div class="detail">
            <?=$info['authorization_erp']?>
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
            <?= $user['user_name'] ?>（<?= $user['real_name'] ?>）
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">代码：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['code'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">备注：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['shop_remark'] ?>
        </div>
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