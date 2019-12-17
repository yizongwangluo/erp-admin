<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href='<?php echo base_url ( 'admin/companyaccount/index' ) ?>'>企业账号列表</a></li>
        <li class="layui-this">新增企业账号</li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/lists' ) ?>'>申请列表</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/unreviewed' ) ?>'>待审批</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/rejected' ) ?>'>已驳回</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/reviewed' ) ?>'>已完成</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <form action="<?php echo base_url ( 'admin/companyaccount/add' ) ?>" class="layui-form" method="post">
                <input type="hidden" value="<?=$info['id']?>"  name="id">
                <div class="layui-form-item">
                    <label class="layui-form-label">企业账户ID</label>
                    <div class="layui-inline col-xs-3">
                        <input name="company_account_id" value="<?= $info['company_account_id'] ?>" type="text" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">网站域名</label>
                    <div class="layui-inline col-xs-3">
                        <select name="shop_id" lay-search="">
                            <option value="">直接选择或搜索选择</option>
                            <?php foreach ($domains as $v): ?>
                                <option value="<?=$v['id']?>" <?php if ( $info['shop_id'] == $v['id'] ){echo "selected=\"selected\"";}?>><?=$v['domain']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">所属企业主体</label>
                    <div class="layui-inline col-xs-3">
                        <select name="company_id" lay-search="">
                            <option value="">直接选择或搜索选择</option>
                            <?php foreach ($company as $v): ?>
                                <option value="<?=$v['id']?>" <?php if ( $info['company_id'] == $v['id'] ){echo "selected=\"selected\"";}?>><?=$v['company_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">所属人</label>
                    <div class="layui-inline col-xs-3">
                        <select name="user_id" lay-search="">
                            <option value="">直接选择或搜索选择</option>
                            <?php foreach ($users as $v): ?>
                                <option value="<?=$v['s_u_id']?>" <?php if ( $info['user_id'] == $v['s_u_id'] ){echo "selected=\"selected\"";}?>><?=$v['s_real_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否解限</label>
                    <div class="layui-inline col-xs-3">
                        <select name="isunlock">
                            <option value="">请选择</option>
                                <option value="是" <?php if ( $info['isunlock'] == "是" ){echo "selected=\"selected\"";}?>>是</option>
                                <option value="否" <?php if ( $info['isunlock'] == "否" ){echo "selected=\"selected\"";}?>>否</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">账户状态</label>
                    <div class="layui-inline col-xs-3">
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
                    <div class="layui-inline col-xs-3">
                        <input name="companyaccount_remark" value="<?= $info['companyaccount_remark'] ?>" type="text" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item" style="text-align: center;width: 50%;">
                    <div class="layui-inline">
                        <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/companyaccount/index' ) ?>" lay-submit lay-filter="post">保存</button>
                    </div>
                    <div class="layui-inline">
                        <a href='<?php echo base_url ( 'admin/companyaccount/index' ) ?>'><button type="button" class="layui-btn">取消</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

<style>
    .layui-form-label{
        width: 100px;
    }

    input{
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }

</style>