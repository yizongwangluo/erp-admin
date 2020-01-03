<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>大龙猫OA系统</title>
    <link rel="stylesheet" href="/static/common/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/admin.css?c=201912231342">
    <script>
        // 定义全局JS变量
        var GV = {
            current_controller: "<?=$this->uri->uri_string()?>",
            base_url: "/static/admin"
        };
    </script>
    <script type="text/javascript" src="/static/admin/js/jquery.min.2.0.js"></script>
    <script type="text/javascript" src="/static/common/layui/layui.js"></script>
</head>
<body>