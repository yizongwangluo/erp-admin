<style>
    .layui-nav-tree .layui-this>a{ background-color: #33aecc; }
</style>
<div class="layui-layout layui-layout-admin">
    <!--头部-->
<div class="layui-header">
    <a class="logo" href="<?=base_url ('admin')?>">
<!--        <img src="/static/admin/images/layui/admin_logo.png">-->
        <span> ERP管理后台 </span>
    </a>
    <ul class="layui-nav">
<!--        <li class="layui-nav-item"><a href="/" target="_blank">【网站首页】</a></li>-->
        <li class="layui-nav-item">
            <a href="javascript:void(0)">当前用户：<?php echo $admin['user_name']; ?></a>
            <dl class="layui-nav-child">
                <dd><a href="javascript:void(0)" data-modal="<?=base_url ('admin/Home/changeMePassword') ?>" data-title="修改【<?php echo $admin['user_name']; ?>】的密码" data-width="450px" >修改密码</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="<?php echo site_url('admin/login/logout');?>" data-url="<?php echo site_url('admin/login/logout');?>" id="clear-cache">【退出】</a></li>
    </ul>
</div>
<!--侧边栏-->
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <ul class="layui-nav layui-nav-tree" lay-filter="menulist">
            <li class="layui-nav-header">
                <i class="layui-icon layui-icon-set"></i><span class="menu-masked">控制台</span>
            </li>
           <?php foreach ($menulist as $vo){
               if (isset($vo['_child'])){ ?>
            <li class="layui-nav-item">
                <a href="javascript:void(0);">
                    <?php if (!empty($vo['icon'])): ?>
                    <i class="<?=$vo['icon']?>"></i>
                    <?php endif; ?><?=$vo['name']?></a>
                <dl class="layui-nav-child">
                    <?php foreach ($vo['_child'] as $v): ?>
                    <dd><a href="<?=base_url ($v['url'])?>" class="admin-page-menu"><?=$v['name']?></a></dd>
                    <?php endforeach;?>
                </dl>
            </li>
            <?php } else{ ?>
            <li class="layui-nav-item">
                <a href="<?=base_url ($vo['url'])?>" class="admin-page-menu">
	                <?php if (!empty($vo['icon'])): ?>
                        <i class="<?=$vo['icon']?>"></i>
	                <?php endif; ?>
                    <?=$vo['name']?>
                </a>
            </li>
            <?php } }?>
        </ul>
    </div>
</div>
<div class="layui-body">