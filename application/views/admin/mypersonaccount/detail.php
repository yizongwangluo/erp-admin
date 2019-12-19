<div class="layui-form-item">
    <label class="layui-form-label">用户名：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['person_username'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">密码：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['person_password'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">RdoIp：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['RdoIp'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">Rdo用户名：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['Rdo_username'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">Rdo密码：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['Rdo_password'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">Rdo端口：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['Rdo_port'] ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">首次登陆时间：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= date('Y-m-d',$info['first_login_time']) ?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">类型：</label>
    <div class="layui-inline">
        <div class="detail">
            <?php
            if($info['type'] == 0){
                echo "大号";
            }else if($info['type'] == 1){
                echo "冷号";
            }else{
                echo "白号";
            }
            ?>
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
    <label class="layui-form-label">状态：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['person_status'] == 0 ? 正常 : 锁号?>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">备注：</label>
    <div class="layui-inline">
        <div class="detail">
            <?= $info['person_remark'] ?>
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