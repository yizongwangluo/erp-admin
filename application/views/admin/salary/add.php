<form class="layui-form layui-modal" action="<?php echo base_url ( 'admin/salary/add' ) ?>" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">底薪设置</label>
        <div class="layui-input-block">
            <input type="text" name="basic_salary" value="<?=$info['basic_salary']?>" required lay-verify="required" placeholder="请输入金额" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label layui-form-label-auto">备注</label>
        <div class="layui-input-block">
            <input type="text" name="salary_remark" value="<?=$info['salary_remark']?>" placeholder="请输入考勤/扣款明细等" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/salary/lists' ) ?>" lay-submit lay-filter="post">保存</button>
            <a href='<?php echo base_url ( 'admin/salary/lists' ) ?>'><button type="button" class="layui-btn ">取消</button></a>
        </div>
    </div>
</form>