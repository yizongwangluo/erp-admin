/**
 * 自定义编辑器组件
 * @dependency xheditor
 * @param handle 表单文本域ID
 */
function custom_editor(handle){
    var xheditor_root='/static/common/js/xheditor';//请务必确认xheditor编辑器的根路径

    var upload_target='!'+xheditor_root+'/xheditor_plugins/multiupload/multiupload.html';
    if(window.custom_editor_init==undefined){
        window.t_watermark='';
        $('body').append('<link rel="stylesheet" type="text/css" href="'+xheditor_root+'/xheditor_skin/default/ui.css"/>');

        var script=document.createElement('script');
        script.type="text/javascript";
        script.id="custom_editor_js";
        script.src=xheditor_root+"/xheditor-zh-cn.min.js";
        document.body.appendChild(script);
        window.custom_editor_init=1;
    }

    var timer=setInterval(function(){
        if($.fn.xheditor!=undefined){
            if(window.editor==undefined){
                window.editor=[];
                window.editor_counter=0;
            }
            window.editor[window.editor_counter]=$('#'+handle).xheditor({upImgUrl:upload_target,upFlashUrl:upload_target,upMediaUrl:upload_target,internalScript:1});
            window.editor_counter++;
            clearInterval(timer);
        }
    },50);

}