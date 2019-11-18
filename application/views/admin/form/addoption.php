<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>
<div class="main clear">
    <div class="tool">
        <a href="<?php echo site_url('admin/form/optionlists/'.$fid);?>" class="button">返回选项列表</a>
    </div>
    <form action="<?php echo site_url('admin/form/saveoption'); ?>" method="post" name="myform">
        <input type="hidden" name="id" value="<?=$id;?>">
        <input type="hidden" name="fid" value="<?=$fid;?>">
        <div class="add_l" style="border-right:none;">
            <div class="add_item">
                <label>选项名称：</label>
                <input name="title" value="<?=$title;?>" type="text" class="txt txtB" />
            </div>
            <div class="add_item">
                <label>选项说明：</label>
                <textarea name="msg" ><?=$msg;?></textarea>
            </div>
            <div class="add_item">
                <label>选项类型：</label>
                <select name="type" onchange="javascript:formtypechange(this.value)">
                    <?=$stext ? '<option value="'.$type.'" selected>'.$stext.'</option>' : '';?>
                    <option value='text'<?=!$stext ? ' selected' : ''?>>单行文本(text)</option>
                    <option value='textarea'>多行文本(textarea)</option>
                    <option value='select'>下拉框(select)</option>
                    <option value='radio'>单选框(radio)</option>
                    <option value='checkbox'>多选框(checkbox)</option>
                    <option value='password'>密码框(password)</option>
                    <option value='hidden'>隐藏域(hidden)</option>
                </select>
            </div>
            <div class="add_item">
                <label>默认值：</label>
                <textarea name="defaultvalue"><?=$defaultvalue;?></textarea>
            </div>
            <div class="add_item" id="trOptions" style="<?=$display ? $display : 'display:none'?>">
                <label>表单选项：</label>
                <textarea name="options" cols="40" rows="5" id="options"><?=$options;?></textarea>
            </div>
            <div class="add_item">
                <label>排列顺序：</label>
                <input type="text" name="orderid" value="<?=$orderid;?>" type="text" class="txt txtA">
            </div>
            <div class="add_item">
                <label>是否必填：</label>
                <input type="radio" class="radio" name="ismust" value="1" checked><span>是</span>
                <input type="radio" class="radio" name="ismust" value="0"><span>否</span>
            </div>
            <script type="text/javascript">
                $("input[type='radio'][value='<?=$ismust;?>']").attr("checked", true);
            </script>
            <div class="btn_box">
                <input type="button" value="保&nbsp;&nbsp;存" onclick="save_form()">
                <input type="button" value="返&nbsp;&nbsp;回" onclick="history.back();">
            </div>
        </div>

    </form>

</div>
<script type="text/javascript">

    function save_form(){
        var form=$('form');
        $.post(form.attr('action'),form.serializeArray(),function(response){
            if(!response.status){
                return alert(response.msg);
            }else{
                alert('保存成功');
                location.reload();
            }


        },'json');
    }

    function formtypechange(val){
        if(val=='select'){
            trOptions.style.display='';
            document.myform.defaultvalue.rows=1;
        }else if(val=='text'){
            trOptions.style.display='none';
            document.myform.defaultvalue.rows=1;
        }else if(val=='textarea'){
            trOptions.style.display='none';
            document.myform.defaultvalue.rows=10;
        }else if(val=='radio'){
            trOptions.style.display='';
        }else if(val=='checkbox'){
            trOptions.style.display='';
        }else{
            trOptions.style.display='none';
            document.myform.defaultvalue.rows=1;
        }
    }

</script>


<?php $this->load->view('admin/common/footer')?>
