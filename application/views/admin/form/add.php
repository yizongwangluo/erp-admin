<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>
<div class="main clear">
    <div class="tool">
        <a href="<?php echo site_url('admin/form/lists');?>" class="button">返回表单列表</a>
    </div>
    <form action="<?php echo site_url('admin/form/save'); ?>" method="post">
        <input type="hidden" name="id" value="<?=$id;?>">
        <div class="add_l" style="border-right:none;">
            <div class="add_item">
                <label>表单名称：</label>
                <input name="fname" value="<?=$fname;?>" type="text" class="txt txtB" />
            </div>
            <div class="add_item">
                <label>表单说明：</label>
                <textarea name="fmsg" ><?=$fmsg;?></textarea>
            </div>
            <div class="add_item">
                <label>表单状态：</label>
                <input type="radio" class="radio" name="display" value="1" checked><span>启用</span>
                <input type="radio" class="radio" name="display" value="0"><span>禁用</span>
            </div>
            <script type="text/javascript">
                $("input[type='radio'][value='<?=$display;?>']").attr("checked", true);
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

</script>


<?php $this->load->view('admin/common/footer')?>
