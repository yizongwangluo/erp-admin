<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class=""><a href='<?php echo base_url ( 'admin/shop/index' ) ?>'>店铺列表</a></li>
        <li class="layui-this">新增店铺</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <form action="<?php echo base_url ( 'admin/shop/add' ) ?>" class="layui-form" method="post">
                <input type="hidden" value="<?=$info['id']?>"  name="id">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">域名 &nbsp;<i style="color: red">https://</i></label>
                        <div class="layui-input-inline">
                            <input type="text" name="domain" value="<?=$info['domain']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">网站后台</label>
                        <div class="layui-input-inline">
                            <input type="text" name="backstage" value="<?=$info['backstage']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">后台用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="backstage_username" value="<?=$info['backstage_username']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">后台密码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="backstage_password" value="<?=$info['backstage_password']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">客服邮箱</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="" name="customer_service_email" value="<?=$info['customer_service_email']?>">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">邮箱密码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email_password" value="<?=$info['email_password']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">收款paypal</label>
                        <div class="layui-input-inline">
                            <input type="text" name="receipt_paypal" value="<?=$info['receipt_paypal']?>" placeholder="" class="layui-input">
                            <em>多个以 , 隔开 最后一个为当前收款paypal</em>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">收款信用卡通道</label>
                        <div class="layui-input-inline">
                            <input type="text" name="receipt_credit_card" value="<?=$info['receipt_credit_card']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">扣款方式</label>
                        <div class="layui-input-inline">
                            <input type="text" name="deduction" value="<?=$info['deduction']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">店铺API密钥</label>
                        <div class="layui-input-inline">
                            <input type="text" name="shop_api_key" value="<?=$info['shop_api_key']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">店铺API密码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="shop_api_pwd" value="<?=$info['shop_api_pwd']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">店铺套餐</label>
                    <div class="layui-inline col-xs-5">
                        <textarea placeholder="请输入内容" class="layui-textarea" name="shop_package"><?=$info['shop_package']?></textarea>
                        <em>多个套餐以 , 隔开</em>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">授权ERP</label>
                        <div class="layui-input-inline">
                            <select name="authorization_erp" lay-search="">
                                <option value="">请选择</option>
                                <option value="是" <?php if($info['authorization_erp'] == "是"){echo "selected=\"selected\"";} ?>>是</option>
                                <option value="否" <?php if($info['authorization_erp'] == "否"){echo "selected=\"selected\"";} ?>>否</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">所属企业主体</label>
                        <div class="layui-input-inline">
                            <select name="company_id" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($company as $v): ?>
                                    <option value="<?=$v['id']?>" <?php if ( $info['company_id'] == $v['id'] ){echo "selected=\"selected\"";}?>><?=$v['company_name']?>(<?=$v['domain']?>)</option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">所属人</label>
                        <div class="layui-input-inline">
                            <select name="user_id" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($users as $v): ?>
                                    <option value="<?=$v['s_u_id']?>" <?php if ( $info['user_id'] == $v['s_u_id'] ){echo "selected=\"selected\"";}?>><?=$v['s_user_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">代码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="code" value="<?=$info['code']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-inline col-xs-3">
                        <input name="shop_remark" value="<?= $info['shop_remark'] ?>" type="text" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item" style="text-align: center;width: 50%;">
                    <div class="layui-inline">
                        <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/shop/index' ) ?>" lay-submit lay-filter="post">保存</button>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn" type="button" onclick="javascript:history.back(-1);">取消</button>
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
    em{
        color: red;
    }

</style>