<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    em{
        color: red;
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href="<?=base_url('admin/goods_sku/index?spu_id='.input('spu_id'))?>">规格列表</a></li>
        <li  class="layui-this">新增规格</li>
        <li><a href="<?=base_url('admin/goods_sku/examine_list?spu_id='.input('spu_id'))?>">审核</a></li>
    </ul>
<form action="<?php echo base_url ( 'admin/goods_sku/save' ) ?>" method="post" class="layui-form">
    <div class="layui-field-box">
    <input type="hidden" name="id" value="<?= $info['id'] ?>">
    <input type="hidden" name="spu_id" value="<?= input('spu_id') ?>">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">*SKU编码：</label>
            <div class="layui-inline">
                <input name="code" lay-verify="required" value="<?= $info['code'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*规格/颜色：</label>
            <div class="layui-inline">
                <input name="norms" lay-verify="required" value="<?= $info['norms'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*价格：</label>
            <div class="layui-inline">
                <input name="price" lay-verify="required" value="<?= $info['price'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">*产品图片：</label>
            <div class="layui-inline">
                <input id="thumb_img" name="img" value="<?=$info['img']?>" type="text" class="layui-input thumb_img" />
            </div>
            <div class="layui-inline">
                <a id="thumb_img_btn"  href="javascript:void(0)" class="layui-btn upload-img-all" >上传图片</a>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">产品包装尺寸：</label>
            <div class="layui-inline">
                <input name="size" lay-verify="required" value="<?= $info['size'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">含包装的重量(克)：</label>
            <div class="layui-inline">
                <input name="weight" lay-verify="required" value="<?= $info['weight'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">采购周期：</label>
            <div class="layui-inline">
                <input name="cycle" lay-verify="required" value="<?= $info['cycle'] ?>" type="text" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">敏感信息：</label>
            <div class="layui-inline">
                <input name="information" lay-verify="required" value="<?= $info['information'] ?>" type="text" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备注：</label>
            <div class="layui-inline">
                <input name="remarks" lay-verify="required" value="<?= $info['remarks'] ?>" type="text" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label for="" class="layui-form-label">类型</label>
            <div class="layui-inline">
                <input <?=empty($info['type'])?'checked':'';?> class="checkbox" type="radio" value="0" name="type" title="普通sku">
                <input <?=$info['type']==1?'checked':'';?> class="checkbox" type="radio" value="1" name="type" title="组合sku">
            </div>
        </div>
        <div class="layui-inline">
            <label for="" class="layui-form-label">测试sku</label>
            <div class="layui-inline">
                <input checked="<?=$info['is_real']==1?'checked':'';?>" class="checkbox" type="radio" value="1" name="is_real" title="是">
                <input checked="<?=$info['is_real']==0?'checked':'';?>" class="checkbox" type="radio" value="0" name="is_real" title="否">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" type="button" onclick="save_form()">保存</button>
        </div>
    </div>
    </div>
</form>
</div>
<script type="text/javascript">
    function save_form() {
        var form = $('form');
        $.post(form.attr('action'), form.serializeArray(), function (response) {
            if (!response.status) {
                layer.msg(response.msg, {time: 2000, icon: 6});
                return false;
            } else {
                layer.msg('保存成功', {time: 2000, icon: 6}, function () {
                    window.location.href = '<?php echo site_url ( 'admin/goods_sku/index' ); ?>';
                })
            }
        }, 'json');
    }
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>