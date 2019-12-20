/**
 * Created by Administrator on 2016/7/22.
 */
$(function(){
   bind_default_val('input,textarea');

});

/**
 * AJAX执行操作
 * @param url
 * @param success_callback
 */
function ajax_operate(url,success_callback){
   if(!confirm("您确定要执行该操作吗？"))return;
   $.getJSON(url,{},function(response){
      if(!response.status){
         return alert(response.msg);
      }

      if(success_callback!=undefined){
         success_callback(response);
      }else{
         window.location.reload();
      }

   })
}

/**
 * 数组中查找元素（类似PHP中的in_array）
 * @param v
 * @returns {boolean}
 */
Array.prototype.has_value=function(v){
   for(var i in this){
      if(this[i]==v){
         return true;
      }
   }
   return false;
}