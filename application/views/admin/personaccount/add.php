<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href='<?php echo base_url ( 'admin/personaccount/index' ) ?>'>个人账号列表</a></li>
        <li class="layui-this">新增个人账号</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <form action="<?php echo base_url ( 'admin/personaccount/add' ) ?>" class="layui-form" method="post">
                <input type="hidden" value="<?=$info['id']?>"  name="id">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="person_username" value="<?=$info['person_username']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="person_password" value="<?=$info['person_password']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">RdoIp</label>
                        <div class="layui-input-inline">
                            <input type="text" name="RdoIp" value="<?=$info['RdoIp']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">Rdo用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="Rdo_username" value="<?=$info['Rdo_username']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">Rdo密码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="Rdo_password" value="<?=$info['Rdo_password']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">Rdo端口</label>
                        <div class="layui-input-inline">
                            <input type="text" name="Rdo_port" value="<?=$info['Rdo_port']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">首次登陆时间</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" id="date" placeholder="请选择日期" name="first_login_time" value="<?= $info['first_login_time'] ? date('Y-m-d',$info['first_login_time']) : '' ?>">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-search="">
                                <option value="">请选择</option>
                                <option value="0" <?php if($info['type'] == "0"){echo "selected=\"selected\"";} ?>>大号</option>
                                <option value="1" <?php if($info['type'] == "1"){echo "selected=\"selected\"";} ?>>冷号</option>
                                <option value="2" <?php if($info['type'] == "2"){echo "selected=\"selected\"";} ?>>白号</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">所属企业主体</label>
                        <div class="layui-input-inline">
                            <select name="company_id" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($company as $v): ?>
                                    <option value="<?=$v['id']?>" <?php if ( $info['company_id'] == $v['id'] ){echo "selected=\"selected\"";}?>><?=$v['company_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">所属人</label>
                        <div class="layui-input-inline">
                            <select name="belongto" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($users as $v): ?>
                                    <option value="<?=$v['s_u_id']?>" <?php if ( $info['belongto'] == $v['s_u_id'] ){echo "selected=\"selected\"";}?>><?=$v['s_user_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">状态</label>
                        <div class="layui-input-inline">
                            <select name="person_status" lay-search="">
                                <option value="">请选择</option>
                                <option value="0" <?php if($info['person_status'] == "0"){echo "selected=\"selected\"";} ?>>正常</option>
                                <option value="1" <?php if($info['person_status'] == "1"){echo "selected=\"selected\"";} ?>>锁号</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="" name="person_remark" value="<?=$info['person_remark']?>">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">cookies</label>
                    <div class="layui-inline col-xs-4">
                        <textarea placeholder="请输入内容" class="layui-textarea" name="cookies"><?=$info['cookies']?></textarea>
                    </div>
                </div>
                <div class="layui-form-item" style="text-align: center;width: 50%;">
                    <div class="layui-inline">
                        <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/personaccount/index' ) ?>" lay-submit lay-filter="post">保存</button>
                    </div>
                    <div class="layui-inline">
                        <a href='<?php echo base_url ( 'admin/personaccount/index' ) ?>'><button type="button" class="layui-btn">取消</button></a>
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

<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;

        //常规用法
        laydate.render({
            elem: '#date'
        });
    });
</script>