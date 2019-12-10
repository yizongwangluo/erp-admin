<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">规格列表</li>
        <li><a href="<?php echo base_url ( 'admin/goods_sku/add?spu_id='.input('spu_id') ) ?>">新增规格</a></li>
        <li><a href="<?=base_url('admin/goods_sku/examine_list?spu_id='.input('spu_id'))?>">审核</a></li>
    </ul>
    <div class="layui-tab-content">
            <!--<form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline  col-xs-2">
                        <input type="text" name="keyword" value="<?/*=$where['keyword']*/?>"
                               class="layui-input" placeholder="输入关键词"/>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
            </form>-->
            <table class="layui-table">
              <thead>
                <tr>
                    <td>ID</td>
                    <td>SKU编码</td>
                    <td>规格/颜色</td>
                    <td class="img">产品图片</td>
                    <td>价格</td>
                    <td>包装尺寸</td>
                    <td>重量（克）</td>
                    <td>采购周期</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$v['code']?></td>
                      <td><?=$v['norms']?></td>
                      <td class="img"><a href="<?=base_url($v['img'])?>" target="_blank">查看</a></td>
                      <td><?=$v['price']?></td>
                      <td><?=$v['size']?></td>
                      <td><?=$v['weight']?></td>
                      <td><?=$v['cycle']?></td>
                      <td style="color: <?=$v['status']!=1?'red':'';?>"><?=$this->enum_field->get_values ( 'is_status' )[$v['status']]?></td>
                      <td>
                          <button style="display: <?=$v['status']?'none':''?>"  data-url="<?=base_url("admin/goods_sku/to_examine/{$v['id']}"); ?>"  class="layui-btn layui-btn-xs confirm_get">提交审核</button>
                          <a style="display: <?=$v['status']==1?'none':'';?>;" class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods_sku/edit/{$v['id']}"); ?>">编辑</a>
                          <button data-url="<?php echo base_url ( 'admin/goods_sku/delete' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>
                      </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <div class="admin-page">
            <?php echo $page_html; ?>
        </div>
    </div>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>
