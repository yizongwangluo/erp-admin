<?php $this->load->view ( 'admin/common/header' ) ?>
<style type="text/css">
    body,.login {
        width: 460px;
        position: fixed;
        top: 50%;
        left: 50%;
        margin-left: -230px;
        margin-top: -230px;
        box-shadow: 0 0 110px #E6E6FA;
    }

    .login .login-title {
        line-height: 45px;
        background: #33aecc;
        border-bottom: 2px solid #33aecc;
        font-size: 20px;
        font-weight: bold;
        color: #fff;
        text-align: center;
    }

    .login .login-form {
        height: 210px;
        padding-top: 35px;
        padding-right: 65px;
        background: #fff;
        opacity: 0.9;
        border-top: 0;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .login .login-form .layui-btn-normal{
        background: #33aecc;
    }
    .layui-form-item{
        margin-bottom: 25px;
    }
</style>
<div>
<div class="login">
<div class="login-title">大龙猫OA系统</div>
<form class="layui-form login-form" action="/admin/login/check" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text"  name="user_name" required lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" name="user_password" required lay-verify="required" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block col-xs-12">
            <button class="layui-btn col-xs-4 layui-btn-normal" lay-submit lay-filter="login">登 录</button>
            <button class="layui-btn col-xs-4 layui-btn-normal" type="reset">重 填</button>
        </div>
    </div>
</form>
    <script  type="text/javascript">
        layui.use(['form'],function () {
            var form = layui.form;
           form.on('submit(login)',function (data) {
               var $data = $(data.form).serializeArray();
               console.log($data);
                $.post(data.form.action,$data,function (res) {
                    if (res.status){
                           window.location.href = '<?=site_url ('admin')?>'
                    }else {
                        layer.alert(res.msg);
                    }
                });
                return false;
           });
        });
    </script>
<?php $this->load->view ( 'admin/common/footer' ) ?>
