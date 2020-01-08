/**
 * Created by storm on 2017/6/21 0021.
 */
layui.use(['layer', 'upload', 'form', 'layedit', 'laydate', 'element'], function () {
    var layer = layui.layer,
        form = layui.form,
        layedit = layui.layedit,
        laydate = layui.laydate,
        element = layui.element;
    /*! 定义当前body对象 */
    this.$body = $('body');
    // 侧边栏事件
    /*element.on('nav(menulist)', function(elem){

        layer.msg(elem.text());

        return false;
    });*/

    /**
     * 后台侧边菜单选中状态
     */
    $('.layui-nav-item').find('a').removeClass('layui-this');
    $('.layui-nav-tree').find('a[href*="' + GV.current_controller + '"]').parent().addClass('layui-this').parents('.layui-nav-item').addClass('layui-nav-itemed');
    /**
     * 封装dom操作
     * @author shaojuntan
     * @type {{confirm_get: confirm_get, confirm_post: confirm_post, ajax: ajax}}
     */
    var JYT = {
        confirm_get: function ($url, $data) {
            layer.confirm('亲，确定执行此操作？', {icon: 3, title: '提示'}, function (index) {
                JYT.ajax_get($url, $data);
            });
        },
        confirm_post: function ($url, $data, $callback) {
            layer.confirm('亲，确定执行此操作？', {icon: 3, title: '提示'}, function (index) {
                JYT.ajax_post($url, $data, $callback);
            });
        },
        ajax_get: function ($url, $data, $callback) {
            if ($data == undefined && $data == '') {
                $data = {};
            }
            $.get($url, $data, function (response) {
                if ($callback != undefined) {
                    return  $callback(response);
                }
                if (!response.status) {
                    return layer.msg(response.msg,{icon:2,time:2000});
                }else {
                    layer.msg('操作成功！', {
                        icon: 6,
                        time: 2000
                    }, function () {
                        window.location.reload();
                    });
                }
            });
        },
        ajax_post: function ($url, $data, $callback) {
            if ($data == undefined) {
                $data = {};
            }
            $.post($url, $data, function (response) {
                if ($callback != undefined) {
                    return  $callback(response);
                }
                if (!response.status) {
                    return layer.msg(response.msg);
                } else {
                        layer.msg('操作成功！', {
                            icon: 1,
                            time: 2000
                        }, function () {
                            window.location.reload();
                        });
                }
            });
        },
        modal:function ($url,$width,$title) {
            this.ajax_get($url,'',function (res) {
                if (typeof (res) === 'object') {
                    return layer.msg(res.msg);
                }
                layer.open({
                    type: 1,
                    title: $title, //标题
                    area: $width,
                    content: res, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                    success:function (dom,index) {
                        var $container = $(dom);
                        $container.find('[data-close]').off('click').on('click', function () {
                            if ($(this).attr('data-confirm')) {
                                layer.confirm($(this).attr('data-confirm'), function () {
                                    layer.close(index);
                                });
                            } else {
                                layer.close(index);
                            }
                        });
                        form.render();
                    }
                });
            });
        }

    }
    //监听POST表单提交
    form.on('submit(post)', function(data){
        var $URL = $(data.elem).attr('data-url');
        if ($URL != undefined){
            JYT.ajax_post(data.form.action,$(data.form).serializeArray(),function (d) {
                if (d.status == 0){
                    layer.alert(d.msg);
                    return false;
                }else if (d.status == 1){
                    layer.msg('操作成功！', {
                        icon: 1,
                        time: 2000
                    }, function () {
                        window.location.href = $URL;
                    });
                }else {
                    layer.alert('服务器超时未响应提示');
                    return false;
                }
            });
        }else {
            JYT.ajax_post(data.form.action,$(data.form).serializeArray());
        }
    });
    //执行带时间验证的get请求
    form.on('submit(list_get_time)',function (data) {
        if (data.field.start_time != '' && data.field.end_time != '') {
            var start_time_stamp = Date.parse(new Date(data.field.start_time));
            var end_time_stamp = Date.parse(new Date(data.field.end_time));
            var time_stamp_diff = end_time_stamp - start_time_stamp;

            if (time_stamp_diff <= 0) {
                layer.alert('查询开始时间必须小于结束时间!');
                return false;
            }
        }
        $(data.form).submit();
    });
    //执行操作
    $('body').on('click','.confirm_get',function () {
        var $url = $(this).attr('data-url');
        JYT.confirm_get($url);
    });
    $('body').on('click','.confirm_post', function () {
        var $url = $(this).attr('data-url');
        var $data_id = $(this).attr('data-id');
        $data ={};
        if($data_id != undefined){
            $data.id=$data_id;
        }
        JYT.confirm_post($url, $data)
    });
    // 监听modal弹出层
    this.$body.on('click','[data-modal]',function () {
        return JYT.modal($(this).attr('data-modal'), $(this).attr('data-width') || '888px', $(this).attr('data-title') || '编辑');
    });
});
function upFiles($elem,$toClass) {
    //上传文件
   layui.use(['upload'],function () {
       var $ = layui.jquery
           ,upload = layui.upload;

       upload.render({
               elem :$elem,
               url: '/admin/editor/upfile/file',
               accept: 'file', //普通文件
               //exts: 'jpg|png|gif|bmp',
               before:function () {
                   //layer.load(); //上传loading
                   layer.msg('正在上传中...', {icon: 16, shade: 0.1, time: 10000})
               },
               done: function (data) {
                   layer.closeAll();
                   if (data.url) {
                       $($toClass).val(data.url);
                   } else {
                       layer.msg(data.state);
                   }
               }
           });
   })
}
function upImgs($elem,$toClass) {
    //上传图片
   layui.use(['upload'],function () {
       var $ = layui.jquery
           ,upload = layui.upload;

       upload.render({
               elem :$elem,
               url: '/admin/editor/upfile/file',
               accept: 'file', //普通文件
               //exts: 'jpg|png|gif|bmp',
               acceptMime: 'image/*', //规定打开文件选择框时，筛选出的文件类型
               before:function () {
                   //layer.load(); //上传loading
                   layer.msg('正在上传中...', {icon: 16, shade: 0.1, time: 10000})
               },
               done: function (data) {
                   layer.closeAll();
                   if (data.url) {
                       $($toClass).val(data.url);
                       $($toClass).attr('src',data.url);
                   } else {
                       layer.msg(data.state);
               }
           }
       });
   })
}

upFiles('.upload-file-all','#thumb_file');
upImgs('.upload-img-all','.thumb_img');

function GUID() {
    this.date = new Date();
    /* 判断是否初始化过，如果初始化过以下代码，则以下代码将不再执行，实际中只执行一次 */
    if (typeof this.newGUID != 'function') {

        /* 生成GUID码 */
        GUID.prototype.newGUID = function() {
            this.date = new Date();
            var guidStr = '';
            sexadecimalDate = this.hexadecimal(this.getGUIDDate(), 16);
            sexadecimalTime = this.hexadecimal(this.getGUIDTime(), 16);
            for (var i = 0; i < 9; i++) {
                guidStr += Math.floor(Math.random()*16).toString(16);
            }
            guidStr += sexadecimalDate;
            guidStr += sexadecimalTime;
            while(guidStr.length < 32) {
                guidStr += Math.floor(Math.random()*16).toString(16);
            }
            return this.formatGUID(guidStr);
        }

        /*
         * 功能：获取当前日期的GUID格式，即8位数的日期：19700101
         * 返回值：返回GUID日期格式的字条串
         */
        GUID.prototype.getGUIDDate = function() {
            return this.date.getFullYear() + this.addZero(this.date.getMonth() + 1) + this.addZero(this.date.getDay());
        }

        /*
         * 功能：获取当前时间的GUID格式，即8位数的时间，包括毫秒，毫秒为2位数：12300933
         * 返回值：返回GUID日期格式的字条串
         */
        GUID.prototype.getGUIDTime = function() {
            return this.addZero(this.date.getHours()) + this.addZero(this.date.getMinutes()) + this.addZero(this.date.getSeconds()) + this.addZero( parseInt(this.date.getMilliseconds() / 10 ));
        }

        /*
        * 功能: 为一位数的正整数前面添加0，如果是可以转成非NaN数字的字符串也可以实现
         * 参数: 参数表示准备再前面添加0的数字或可以转换成数字的字符串
         * 返回值: 如果符合条件，返回添加0后的字条串类型，否则返回自身的字符串
         */
        GUID.prototype.addZero = function(num) {
            if (Number(num).toString() != 'NaN' && num >= 0 && num < 10) {
                return '0' + Math.floor(num);
            } else {
                return num.toString();
            }
        }

        /*
         * 功能：将y进制的数值，转换为x进制的数值
         * 参数：第1个参数表示欲转换的数值；第2个参数表示欲转换的进制；第3个参数可选，表示当前的进制数，如不写则为10
         * 返回值：返回转换后的字符串
         */
        GUID.prototype.hexadecimal = function(num, x, y) {
            if (y != undefined) {
                return parseInt(num.toString(), y).toString(x);
            } else {
                return parseInt(num.toString()).toString(x);
            }
        }

        /*
         * 功能：格式化32位的字符串为GUID模式的字符串
         * 参数：第1个参数表示32位的字符串
         * 返回值：标准GUID格式的字符串
         */
        GUID.prototype.formatGUID = function(guidStr) {
            var str1 = guidStr.slice(0, 8) + '-',
                str2 = guidStr.slice(8, 12) + '-',
                str3 = guidStr.slice(12, 16) + '-',
                str4 = guidStr.slice(16, 20) + '-',
                str5 = guidStr.slice(20);
            return str1 + str2 + str3 + str4 + str5;
        }
    }
}