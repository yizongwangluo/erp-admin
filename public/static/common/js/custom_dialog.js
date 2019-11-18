
/**
 * 自定义弹窗（注意：构造函数的参数都是JQ DOM对象）
 * @param main_frame 主框架
 * @param title_bar 标题栏
 * @param content_view 内容视图
 * @param close_btn 关闭按钮
 * @param mask_layer 遮罩层 (非必需)
 * @returns {custom_dialog}
 */
function custom_dialog(main_frame,title_bar,content_view,close_btn,mask_layer){

    this.main_frame=$(main_frame);
    this.title_bar=$(title_bar);
    this.content_view=$(content_view);
    this.close_btn=$(close_btn);
    this.mask_layer=mask_layer==undefined?null:$(mask_layer);

    var this_obj=this;

    //初始化
    this.init=function(){

        if(mask_layer==null){
            if($('#mask_layer').size()==0){
                this.main_frame.before('<div style="display: none;" id="mask_layer"></div>');
            }
            this.mask_layer=$('#mask_layer');
        }

        //默认隐藏窗口
        this.hide();

        //绑定关闭事件
        this.close_btn.click(function(){
            this_obj.hide();
        });

        //窗口居中
        this.mask_layer.css({
            'width'   :'100%',
            'height'  :'100%',
            'background-color':'black',
            'position':'fixed',
            'opacity' :0.3,
            'top'     :0,
            'left'    : 0

        });
        this.main_frame.css({
            'position':'fixed',
            'top'     :'50%',
            'left'    : '50%'
        });

    };



    //显示弹窗
    this.show=function(){
        this.mask_layer.fadeIn(100);
        this.main_frame.fadeIn(300);
        this.main_frame.css({
            'margin-top':'-'+(this.main_frame.outerHeight()/2)+'px',
            'margin-left':'-'+(this.main_frame.outerWidth()/2)+'px'
        });
        return this;
    };

    //隐藏弹窗
    this.hide=function(){
        this.mask_layer.fadeOut(300);
        this.main_frame.fadeOut(300);
        return this;
    };

    //设置标题
    this.set_title=function(title){
        this.title_bar.text(title);
        return this;
    };

    //设置内容
    this.set_content=function(content){
        this.content_view.html(content);
        return this;
    };

    this.init();
}