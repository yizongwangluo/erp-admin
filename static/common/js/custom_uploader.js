/**
 * 自定义上传组件
 * @dependency xheditor,custom_dialog
 * @param handle 上传按钮ID
 * @param receiver 接收上传结果的input输入框ID
 */
function custom_uploader(handle,receiver){
    var xheditor_root='/static/common/js/xheditor';//请务必确认xheditor编辑器的根路径

    $('#'+handle).click(function(){

        $('.xheModal').remove();
        $('body').append('<link type="text/css" href="'+xheditor_root+'/xheditor_skin/default/ui.css" rel="stylesheet" />');
        $('body').append('' +
            '<div style="width: 349px; height: 260px;display: none;" class="xheModal">' +
            '   <div class="xheModalTitle">' +
            '       <span title="关闭 (Esc)" class="xheModalClose"></span>上传文件' +
            '   </div>' +
            '   <div class="xheModalContent" style="height: 220px;">' +
            '       <iframe frameborder="0" style="width: 100%; height: 100%;" src="'+xheditor_root+'/xheditor_plugins/multiupload/multiupload.html?watermark=0"></iframe>' +
            '   </div>' +
            '</div>' +
            '');
        var custom_uploader_dialog=new custom_dialog($('.xheModal'),null,null,$('.xheModal .xheModalClose'));
        custom_uploader_dialog.close_btn.on('click',function(){
            $('.xheModal').remove();
        })
        custom_uploader_dialog.show();
        window.xheditor_upload_success=function(url){
            $('#'+receiver).val(url).attr('src',url).show();
            custom_uploader_dialog.hide();
            $('.xheModal').remove();
        }

    });


}