<html>
<head>
    <meta charset="UTF-8">
    <title>交易兔微信支付收银台</title>
    <script type="text/javascript" src="//www.yuu1.com/js/jquery.min.js"></script>
</head>
<body>
<script type="text/javascript" src="/static/common/js/custom_dialog.js"></script>
<style type="text/css">
    /* wxzf弹窗 */
    @font-face{font-family:"semibold";src:url('/static/home/css/font/semibold.eot');src:url('/static/home/css/font/semibold.woff2') format('woff2'),url('/static/home/css/font/semibold.woff') format('woff')}
    .clear-fix:after{content:"";display:block;clear:both;}
    p{margin:0;}
    em{font-style: normal;color:#f40;}
    .weixin-pop{padding:40px;width:880px;background:#fff;box-shadow:0 0 20px rgba(0,0,0,.2);}
    .weixin-tit img{width:120px;height:38px;}
    .weixin-tit span{margin-left:20px;line-height:38px;font-size:24px;color:#4c4c4c;vertical-align:bottom;}
    .weixin-message{padding:50px 0;font-size:14px;border-bottom:1px dashed #ccc;}
    .message-order{float:left;width:50%;}
    .message-order span{display:block;}
    .message-money{float:right;width:50%;text-align:right;}
    .message-money em{font-family:"semibold";font-size:24px;font-weight:bold;color:#ff0000;}
    .weixin-center{margin-top:50px;}
    .weixin-center .weixin-scan{padding-left:52px;line-height:40px;font-size: 26px;color:#666;background:url("/static/home/images/wx_icon.png") no-repeat;}
    .weixin-wrap{position:relative;margin:30px 52px;}
    .weixin-two-code{float:left;}
    .weixin-img{position:relative;margin:20px 0;padding:20px;width:258px;height:258px;border: 1px solid #dfdfdf;overflow: hidden;}
    .weixin-img img{width:260px;height:260px;}
    .weixin-explain{height:64px;background:url("/static/home/images/explain_icon.png")no-repeat 50px #f6a934;}
    .weixin-explain span{display:inline-block;margin:10px 0 0 124px;width:134px;color:#fff;}
    .weixin-img-tip{float:right;margin-right:40px;width:329px;height:421px;}
    .weixin-img-mask{position: absolute;left:0;top: 0;right:0;bottom:0;background:rgba(0,0,0,0.4);}
    .weixin-img-mask img{display:block;margin:109px auto;width:80px;height:80px;}
    .weixin-time{text-align:center;font-size:14px;}
    .weixin-other{margin:20px 50px;padding-left:12px;background:url("/static/home/images/pay_more.png") no-repeat;}
    .weixin-other a{line-height:14px;color: #666;text-decoration: none;font-size: 12px}
    .weixin-other a:hover{text-decoration:underline;}
</style>
<!-- 微信 弹窗-->
<div class="weixin-pop">
    <div class="weixin-tit">
        <img src="/static/home/images/wx_pay_logo.png">
        <span>收银台</span>
    </div>
    <div class="clear-fix weixin-message">
        <div class="message-order">
            <span>订单编号：<?php echo $order_number; ?></span>
            <span>物品详情：<?php echo $order_name;?></span>
        </div>
        <span class="message-money">应付金额<em><?php echo $total_fee; ?></em>元</span>
    </div>
    <div class="weixin-center">
        <div class="weixin-scan">微信支付</div>
        <div class="clear-fix weixin-wrap">
            <div class="weixin-two-code">
                <p class="weixin-time">二维码有效时间<em>5分钟</em>，请尽快完成交易</p>
                <div class="weixin-img">
                    <img src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url);?>">
                    <div class="weixin-img-mask" style="display:none;"><a href="javascript:;" onclick="reload()"><img src="/static/home/images/weixin_refresh.png"></a></div>
                </div>
                <div class="weixin-explain">
                    <span>请使用微信扫一扫扫描二维码支付</span>
                </div>
            </div>
            <img src="/static/home/images/weixin_tip.jpg" class="weixin-img-tip">
        </div>
        <div class="weixin-other">
            <a href="javascript:payment_reselect()">使用其他支付方式</a>
        </div>
    </div>
</div>
<!-- 微信 弹窗 end-->
<script type="text/javascript">

    var dialog=new custom_dialog($('.weixin-pop'));
    dialog.show();

    //自动检测支付状态
    var timer=setInterval(function(){
        $.get('/payment_gateway/query_status',{order_number:'<?php echo $order_number; ?>'},function(status){
            if(status=='ok'){
                clearInterval(timer);
                window.location.href="/payment_gateway/back/wxpay?out_trade_no=<?php echo $order_number; ?>";

            }
        });
    },2000);

    function payment_reselect(){
        window.opener.location.reload();
        window.close();
    }

    function reload() {
        window.location.reload();
    }

    var maxtime = 60 * 5;
    function CountDown() {
        if (maxtime >= 0) {
            minutes = Math.floor(maxtime / 60);
            seconds = Math.floor(maxtime % 60);
            if (maxtime == 0) $('.weixin-img-mask').show();
            --maxtime;
        } else{
            clearInterval(timer);
        }
    }
    timer = setInterval("CountDown()", 1000);


</script>
</body>
</html>