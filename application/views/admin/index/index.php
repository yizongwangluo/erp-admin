<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>

<style>
    .layui-nav-tree .layui-nav-item {
        border-top: 1px solid #fff;
    }
    .layui-side .layui-nav .layui-this>a{
        color: #fff;
    }
    .layui-side .layui-nav .layui-this>a:hover{
        color: #000;
    }
    body{
       background-color: #f2f2f2;
    }

    .layui-layout-admin .layui-body{
        background: none;
        left:200px;
        top:60px;
    }
    .layui-card-header-1{
        font-size: 20px;
        padding: 5px 2px 5px 15px;
    }
    .xs2-div-ss{
        margin: 10px 15px;
    }
    .xs2-div-ss h3{
        color: #999;
    }
    .xs2-div-ss:hover{
        background-color: #f2f2f2;
    }
    .layadmin-backlog-body p cite {
        font-style: normal;
        font-size: 30px;
        font-weight: 300;
        color: #009688;
        line-height: 40px;
    }

</style>

<div class="layui-fluid">

    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header-1">待办事项
            </div>
            <hr/>
            <div class="layui-card-body">

                <div class="layui-carousel layadmin-carousel layadmin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 120px;">
                    <div carousel-item="">
                        <ul class="layui-row layui-col-space10 layui-this">
                            <li class="layui-col-xs3 xs2-div-ss">
                                <a href="/admin/advert/index?status=0" class="layadmin-backlog-body">
                                    <h3>广告申请</h3>
                                    <p><cite><?=$advert_sum?></cite></p>
                                </a>
                            </li>
                            <li class="layui-col-xs3  xs2-div-ss">
                                <a href="/admin/order/index?error_order=0" class="layadmin-backlog-body">
                                    <h3>异常订单</h3>
                                    <p><cite><?=$order_error?></cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('admin/common/footer')?>
