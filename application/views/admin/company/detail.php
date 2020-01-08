<div class="layui-form-item">
    <label class="layui-form-label">代理商：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['agent'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">公司名称：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['company_name'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">域名：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['domain'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">营业执照图片：</label>
    <div class="layui-inline">
        <div class="detail">
            <img src="<?= $info['business_license_image'] ?>" style="width: 150px;height: 150px;margin-right: 20px">
            <a href="<?= $info['business_license_image'] ?>" class="layui-btn layui-btn-primary layui-btn-xs" target="_blank">查看原图</a>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">广告主联系人姓名：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['ad_connect_name'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">广告主联系人邮箱：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['ad_connect_email'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">时区：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['time_zone'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">BM：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['BM'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">开户状态：</label>
    <div class="layui-inline">
        <div class="detail">
            <?php
            if($info['account_status'] == 0){
                echo "审核成功";
            }else if($info['account_status'] == 1){
                echo "审核中";
            }else{
                echo "审核失败";
            }
            ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">下户时间：</label>
    <div class="layui-inline">
        <div class="detail">
            <?=  date('Y-m-d',$info['logout_time']) ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">BM API：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['BMAPI'] ?>
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
    <label class="layui-form-label">备注：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['company_remark'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">FB粉丝页链接：</label>
    <div class="layui-inline">
        <div class="detail">
            <?php
            if(strpos($info['fanslink'],',') !== false) {
                $fanslinks = explode(',',$info['fanslink']);
                foreach ($fanslinks as $fanslink){
                    echo $fanslink."<br>";
                }
            }else{
                echo $info['fanslink'];
            }
            ?>

        </div>
    </div>
</div>
<style>
    .layui-form-item{
        margin-bottom: 5px;
    }
    .layui-form-label{
        width: 130px;
    }

    .detail{
        padding: 10px;
    }
</style>