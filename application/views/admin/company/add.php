<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class=""><a href='<?php echo base_url ( 'admin/company/index' ) ?>'>企业主体列表</a></li>
        <li class="layui-this">新增企业主体</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"  >
            <form action="<?php echo base_url ( 'admin/company/add' ) ?>" class="layui-form" method="post">
                <input type="hidden" value="<?=$info['id']?>"  name="id">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">*代理商</label>
                        <div class="layui-input-inline">
                            <input type="text" name="agent" value="<?=$info['agent']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">*公司名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="company_name" value="<?=$info['company_name']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">*域名 &nbsp;<i style="color: red">https://</i></label>
                        <div class="layui-input-inline">
                            <input type="text" name="domain" value="<?=$info['domain']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">*营业执照</label>
                        <div class="layui-inline">
                            <img src="<?php echo $info['business_license_image'] ? : '';?> "  class="thumb_img" style="width: 150px;height: 150px"/>
                            <input id="thumb_img" name="business_license_image" value="<?=$info['business_license_image']?>" type="hidden" class="layui-input thumb_img" />
                        </div>
                        <div class="layui-inline">
                            <a id="thumb_img_btn"  href="javascript:void(0)" class="layui-btn upload-img-all" >上传图片</a>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">*广告主联系人姓名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="ad_connect_name" value="<?=$info['ad_connect_name']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">*广告主联系人邮箱</label>
                        <div class="layui-input-inline">
                            <input type="text" name="ad_connect_email" value="<?=$info['ad_connect_email']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">FB粉丝页链接</label>
                        <div class="layui-input-inline">
                            <input type="text" name="fanslink" value="<?=$info['fanslink']?>" placeholder="" class="layui-input">
                            <em>多个链接以 , 隔开</em>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">时区</label>
                        <div class="layui-input-inline">
                            <input type="text" name="time_zone" value="<?=$info['time_zone']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">BM</label>
                        <div class="layui-input-inline">
                            <input type="text" name="BM" value="<?=$info['BM']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">开户状态</label>
                        <div class="layui-input-inline">
                            <select name="account_status" lay-filter="account_status">
                                <option value="">请选择</option>
                                <option value="1" <?php if($info['account_status'] == "1"){echo "selected=\"selected\"";} ?>>审核中</option>
                                <option value="0" <?php if($info['account_status'] == "0"){echo "selected=\"selected\"";} ?>>审核成功</option>
                                <option value="2" <?php if($info['account_status'] == "2"){echo "selected=\"selected\"";} ?>>审核失败</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">下户时间</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" id="date" placeholder="请选择日期" name="logout_time" value="<?= $info['logout_time'] ? date('Y-m-d',$info['logout_time']) : '' ?>">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">BM API</label>
                        <div class="layui-input-inline">
                            <input type="text" name="BMAPI" value="<?=$info['BMAPI']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">所属人</label>
                        <div class="layui-input-inline">
                            <select name="belong_to" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($users as $v): ?>

                                    <?php if(!$v['is_disable']){ ?>
                                        <option value="<?=$v['s_u_id']?>" <?php if ( $info['belong_to'] == $v['s_u_id'] ){echo "selected=\"selected\"";}?>><?=$v['s_user_name']?></option>
                                    <?php   } ?>

                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">备注</label>
                        <div class="layui-input-inline">
                            <input type="text" name="company_remark" value="<?=$info['company_remark']?>" placeholder="" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item" style="text-align: center;width: 50%;">
                    <div class="layui-inline">
                        <button type="button" class="layui-btn" data-url="<?php echo base_url ( 'admin/company/index' ) ?>" lay-submit lay-filter="post">保存</button>
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

<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //常规用法
        laydate.render({
            elem: '#date'
        });
    });
</script>
<style>
    .layui-form-label{
        width: 120px;
    }

    input{
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }

    em{
        color: red;
        padding-left: 5px;
    }

</style>