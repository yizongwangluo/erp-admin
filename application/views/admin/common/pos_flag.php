<div class="layui-form-item">
    <label class="layui-form-label">推荐标识：</label>
    <div class="layui-inline col-xs-8">
        <input name="pos_flag" type="hidden" value="<?php echo $pos_flag; ?>">
        <?php foreach($this->enum_field->get_values('pos_flag') as $k=>$v): ?>
            <input class="checkbox select_pos_flag" type="checkbox" value="<?php echo $k; ?>" title="<?php echo $v; ?>" />
        <?php endforeach; ?>
    </div>

    <script type="text/javascript">
        $('.select_pos_flag').click(function(){
            var checked_flags=[];
            $('.select_pos_flag').map(function(){
                if(this.checked){
                    checked_flags.push($(this).val());
                }
            });
            $('input[name=pos_flag]').val(checked_flags.join(','));
        });
        $('input[name=pos_flag]').val().split(',').map(function(v){
            $('.select_pos_flag[value='+v+']').attr('checked',true);
        });
    </script>
</div>