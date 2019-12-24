<form action="<?php echo base_url ( 'admin/companyaccount/add' ) ?>" class="layui-form layui-modal" method="post">
    <input type="hidden" value="<?=$info['id']?>"  name="id">
    <div class="layui-form-item">
        <label class="layui-form-label">企业账户ID</label>
        <div class="layui-input-block">
            <input name="company_account_id" value="<?= $info['company_account_id'] ?>" type="text" class="layui-input" placeholder="请输入企业账户ID">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">网站域名</label>
        <div class="layui-input-block">
            <select name="shop_id" lay-search="">
                <option value="">直接选择或搜索选择</option>
                <?php foreach ($domains as $v): ?>
                                <option value="<?=$v['id']?>" <?php if ( $info['shop_id'] == $v['id'] ){echo "selected=\"selected\"";}?>><?=$v['domain']?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否解限</label>
        <div class="layui-input-block">
            <select name="isunlock">
                <option value="">请选择</option>
                <option value="是" <?php if ( $info['isunlock'] == "是" ){echo "selected=\"selected\"";}?>>是</option>
                <option value="否" <?php if ( $info['isunlock'] == "否" ){echo "selected=\"selected\"";}?>>否</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">账户状态</label>
        <div class="layui-input-block">
            <select name="status" lay-filter="status">
                <option value="">请选择</option>
                <option value= '0' <?php if ( $info['status'] == '0' ){echo "selected='selected'";}?>>正常</option>
                <option value= '1' <?php if ( $info['status'] == '1' ){echo "selected='selected'";}?>>封户</option>
                <option value= '2' <?php if ( $info['status'] == '2'){echo "selected='selected'";}?>>申诉中</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <input name="companyaccount_remark" value="<?= $info['companyaccount_remark'] ?>" type="text" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/company/edit/'.$_SERVER['QUERY_STRING'] ) ?>" lay-submit lay-filter="post">保存</button>
            <a href='<?php echo base_url ( 'admin/company/edit/'.$_SERVER['QUERY_STRING'] ) ?>'><button type="button" class="layui-btn ">取消</button></a>
        </div>
    </div>

</form>

