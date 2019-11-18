<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>
<div class="main clear">
    <div class="tool">
        <a href="<?php echo site_url('admin/form/lists');?>" class="button">返回表单列表</a>
    </div>
    <form action="<?php echo site_url('admin/form/contentsave'); ?>" method="post">
        <input type="hidden" name="id" value="<?=$id;?>">
        <div class="add_l" style="width:50%;border-right:none;">

            <?=$option;?>

            <div class="btn_box" style="width: 45%;">
                <input type="button" value="提&nbsp;&nbsp;交" onclick="save_form()">
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
