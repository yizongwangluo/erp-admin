<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .em-red{
        color: red;
        padding-right: 5px;
    }
</style>
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
                        <label class="layui-form-label"><em class="em-red">*</em>域名 &nbsp;<i style="color: red">https://</i></label>
                        <div class="layui-input-inline">
                            <input type="text" name="domain" value="<?=$info['domain']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label"><em class="em-red">*</em>后台 &nbsp;<i style="color: red">https://</i></label>
                        <div class="layui-input-inline">
                            <input type="text" name="backstage" value="<?=$info['backstage']?>" placeholder="" class="layui-input">
                        </div>
                        <div style="float: left;padding: 9px 0;text-align: left;"><em>（例：elvsis.myshopify.com/admin/）</em></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label"><em class="em-red">*</em>所属平台</label>
                        <div class="layui-input-inline">
                            <select name="pt_id" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach($this->enum_field->get_values('shop_pt_list') as $key=>$value){ ?>
                                    <option value="<?=$key?>" <?php if ( $info['pt_id'] == $key ){echo "selected=\"selected\"";}?>><?=$value?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">后台用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="backstage_username" value="<?=$info['backstage_username']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">后台密码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="backstage_password" value="<?=$info['backstage_password']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
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
                    <div class="layui-inline">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-inline">
                            <input name="shop_remark" value="<?= $info['shop_remark'] ?>" type="text" class="layui-input">
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
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                           <a class="layui-btn  layui-btn-normal" id="jiaoyan">API校验</a>
                        </div>
                    </div>
                    </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">时区/时间差</label>
                        <div class="layui-input-inline">
                            <input type="text" name="timezone" value="<?=$info['timezone']?>" placeholder="" class="layui-input">
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
                        <label class="layui-form-label"><em class="em-red">*</em>授权ERP</label>
                        <div class="layui-input-inline">
                            <select name="authorization_erp" lay-search="">
                                <option value="">请选择</option>
                                <option value="是" <?php if($info['authorization_erp'] == "是"){echo "selected=\"selected\"";} ?>>是</option>
                                <option value="否" <?php if($info['authorization_erp'] == "否"){echo "selected=\"selected\"";} ?>>否</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label"><em class="em-red">*</em>所属企业主体</label>
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
                        <label class="layui-form-label"><em class="em-red">*</em>所属人</label>
                        <div class="layui-input-inline">
                            <select name="user_id" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($users as $v): ?>
                                    <option value="<?=$v['s_u_id']?>" <?php if ( $info['user_id'] == $v['s_u_id'] ){echo "selected=\"selected\"";}?> <?=$v['is_disable']?'disabled':''?> ><?=$v['s_user_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label"><em class="em-red">*</em>通途代码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="code" value="<?=$info['code']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">开关</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="status" <?=$info['status']==1?'checked':''?>  lay-skin="switch" value="1" lay-text="开启|关闭">
                        </div>
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
<script>
    layui.use(['layer'], function(){

        var layer = layui.layer;

        $('#jiaoyan').click(function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            var backstage = $('input[name="backstage"]').val(),
                shop_api_key = $('input[name="shop_api_key"]').val(),
                shop_api_pwd = $('input[name="shop_api_pwd"]').val();
            $.post('/admin/ShopifyApi/getShopInfo',
                        {backstage:backstage,
                        shop_api_key:shop_api_key,
                        shop_api_pwd:shop_api_pwd},
                function(obj){
                    console.log(obj);
                    if(obj.code){
                        layer.msg('校验成功');
                        $('input[name="timezone"]').val(obj.data.timezone);
                    }else{
                        layer.msg(obj.msg);
                    }
                    layer.close(index);
                },'json')
        })
    });
</script>