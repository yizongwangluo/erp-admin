<input name="pos_device" class="checkbox select_pos_device" type="hidden" value="<?= $device; ?>">
<label class="layui-form-label layui-form-label-auto">选择设备：</label>
<div class="layui-inline">
	<?php foreach ( $this->enum_field->get_values ( 'device_type' ) as $key => $item ): ?>
        <input title="<?= $item; ?>" type="checkbox" name="device[]" class="checkbox select_pos_device" value="<?= $key; ?>"/>
	<?php endforeach; ?>
</div>
<script type="text/javascript">
    $('input[name=pos_device]').val().split(',').map(function (v) {
        $('.select_pos_device[value=' + v + ']').attr('checked', true);
    });
</script>