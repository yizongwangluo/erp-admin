<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>操作提示</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
        .system-message{ padding: 24px 48px; margin:100px auto; text-align:center}
        .system-message h1{ font-size: 20px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .system-message .jump{ padding-top: 10px}
        .system-message .jump a{ color: #333;}
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
        @media (max-width: 768px) {
            .system-message{
                margin: 60px auto;
            }
            .system-message h1{
                font-size: 90px;
                line-height:110px;
            }
            .system-message .success,.system-message .error{
                font-size: 30px;
                line-height: 1.6em;
            }
        }
    </style>
</head>
<body>
<div class="system-message">
    <?php if(!empty($code)) {?>
        <h1><img src="data:image/png;base64,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"/></h1>
        <p class="success"><?php echo(strip_tags($msg));?></p>
    <?php }else{?>
        <h1><img src="data:image/png;base64,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"/></h1>
        <p class="error"><?php echo(strip_tags($msg));?></p>
    <?php }?>
    <p class="detail"></p>
    <p class="jump">
        页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($time);?></b>
    </p>
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>
<?php exit(); ?>