/**
 * Created by Administrator on 2016/7/22.
 */
/**
 * 表单默认值显示组件
 */


/**
 * 过虑表单默认值
 * @param data
 * @returns {*}
 */
function filter_default_val(data){
    //标准json key:value格式
    if(data.length==undefined){
        for(var k in data){
            var submit_value=data[k];
            var default_value=$('input[name='+k+']').attr('input_default_value');
            if(submit_value==default_value){
                data[k]='';
            }
        }

    //jquery标准序列化数据格式
    }else{
        jQuery.each(data,function(i,field){
            var submit_value=field.value;
            var default_value=$('input[name='+field.name+']').attr('input_default_value');
            if(submit_value==default_value){
                data[i].value='';
            }
        });
    }

    return data;
}

/**
 * 绑定表彰默认值
 * @param input
 */
function bind_default_val(input){

    $(input).map(function(){

        if($(this).attr('input_default_value')==undefined){
            return;
        }
        if($(this).attr('already_bound')==1){
            return;
        }


        $(this).attr('already_bound',1);

        if(window.bind_default_val_counter==undefined){
            window.bind_default_val_counter=1;
        }
        var is_pwd=$(this).attr('type')=='password';
        var this_copy_id='input_'+(window.bind_default_val_counter++)+'_tmp_copy';
        var this_obj=$(this);
        var this_copy=null;
        if(is_pwd){
            $(this).before("<input type='text' style='"+$(this).attr('style')+"' value='"+$(this).attr('input_default_value')+"' id='"+this_copy_id+"' class='"+$(this).attr('class')+" empty_value' >");
            this_copy=$('#'+this_copy_id);
            if($(this).val()==''){
                $(this).hide();
            }else{
                this_copy.hide();
            }
            this_copy.focus(function(){
                this_copy.hide();
                this_obj.show().focus();
            });

        }


        if(!is_pwd && $(this).val()==''){
            $(this).val($(this).attr('input_default_value')).addClass('empty_value');
        }

        $(this).focus(function(){
            if($(this).val()==$(this).attr('input_default_value')){
                $(this).val('').removeClass('empty_value');
            }

            if(is_pwd){

            }


        }).blur(function(){
            if($(this).val()==''){
                if(!is_pwd){
                    $(this).val($(this).attr('input_default_value')).addClass('empty_value');
                }

                if(is_pwd){
                    this_obj.hide();
                    this_copy.show().addClass('empty_value');
                }
            }
        });
    });
}