<html>
<body>
<script type="text/javascript" src="http://www.yuu1.com/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/common/js/custom_dialog.js"></script>
<style type="text/css">
    /* wxzf弹窗 */
    em{font-style: normal;}
    .weixin-pop{width: 460px;height: 420px;background:#fff;border:8px solid #b1b1b1;text-align: center;}
    .weixin-title{height: 50px;background: #4b5b78;color: #fff;font-size: 18px;line-height: 50px;padding-left: 20px;text-align: left;}
    .weixin-center{width: 400px;margin: 0 auto;}
    .weixin-center .weixin-scan{font-size: 24px;margin: 26px 0 6px}
    .wenxin-wrap{position:relative;}
    .wenxin-img{border: 1px solid #dfdfdf;padding:9px;overflow: hidden;margin: 0 auto;width: 170px;height: 170px,}
    .wenxin-img img{width: 170px;height: 170px,}
    .wenxin-img-mask{width: 190px;height: 194px;position: absolute;left: 50%;top: 0;background:rgba(0,0,0,0.4);margin-left: -95px;}
    .wenxin-img-mask p{background: #fff;padding: 5px 10px;width: 130px;margin: 80px 0 0 20px;}
    .weixin-img-tip{background:url("/static/home/images/weixin-tip.png") no-repeat;width: 290px;height:416px;position: absolute;left: 50%;top:-110px;margin-left: 240px;display: none;}
    .wenxin-time{margin:10px auto 0;border-bottom: 1px solid #b1b1b1;padding-bottom: 20px}
    .wenxin-time em{color:#f40;}
    .wenxin-other{margin: 15px 0 0}
    .wenxin-other a{color: #666;text-decoration: none;font-size: 12px}
</style>
<!-- 微信 弹窗-->
<div class="weixin-pop">
    <div class="weixin-title">支付<em><?php echo $qrcode_info['total_fee']; ?></em>元</div>
    <div class="weixin-center">
        <div class="weixin-scan">微信扫码支付</div>
        <div class="wenxin-wrap">
            <div class="wenxin-img">
                <img src="<?php echo $qrcode_info['img_url']; ?>">
            </div>
            <!--<div class="wenxin-img-mask"><p>重新获取二维码</p></div> -->
            <div class="weixin-img-tip"></div>
        </div>
        <div class="wenxin-time">二维码有效期<em>5分钟</em></div>
        <!--<div class="wenxin-time"><em>二维码已过期,请重新获取二维码。</em></div>-->
        <div class="wenxin-other">
            <a href="javascript:payment_reselect()">使用其他支付方式></a>
        </div>
    </div>
</div>
<!-- 微信 弹窗 end-->
<script type="text/javascript">

    var dialog=new custom_dialog($('.weixin-pop'));
    dialog.show();


    $(function(){
        $(".weixin-pop").hover(function(){
            $(".weixin-img-tip").show()
        },function(){
            $(".weixin-img-tip").hide()
        })
    })


    //自动检测支付状态
    var timer=setInterval(function(){

        $.get('/payment_gateway/query_status',{order_number:'<?php echo $qrcode_info['out_trade_no']; ?>'},function(status){
            if(status=='ok'){
                clearInterval(timer);
                window.location.href="/payment_gateway/back/wxpay?out_trade_no=<?php echo $qrcode_info['out_trade_no']; ?>";

            }
        });

    },2000);


    function payment_reselect(){
        window.opener.location.reload();
        window.close();
    }


</script>

</body>
</html>