<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">商品列表</li>
        <li><a href="<?php echo base_url ( 'admin/goods/add' ) ?>">新增商品</a></li>
        <li><a href="<?=base_url('admin/goods/examine_list')?>">审核</a></li>
    </ul>
    <div class="layui-tab-content">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline  col-xs-3">
                        <input type="text" name="keyword" value="<?=$where['keyword']?>"
                               class="layui-input" placeholder="输入关键词"/>
                    </div>
                    <div class="layui-inline">
                        <select name="status" lay-verify="required">
                            <option value="">请选择</option>
                            <?php foreach($this->enum_field->get_values('is_status') as $key=>$value){ ?>
                                <option value="<?php echo $key ?>" <?php if(is_numeric($where['status']) && $where['status']==$key){ echo 'selected'; } ?> ><?php echo $value ?></option>
                            <?php   } ?>
                        </select>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
            </form>
            <table class="layui-table">
              <thead>
                <tr>
                    <td>ID</td>
                    <td>SPU编码</td>
                    <td>产品名</td>
                    <td class="img">产品图片</td>
                    <td>销量</td>
                    <td>起批量</td>
                    <td>运费</td>
                    <td>创建人</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$v['code']?></td>
                      <td><?=$v['name']?></td>
                      <td class="img"><a href="<?=base_url($v['img'])?>" target="_blank">查看</a></td>
                      <td><?=$v['sales_volume']?></td>
                      <td><?=$v['batch_quantity']?></td>
                      <td><?=$v['freight']?></td>
                      <td><?=$v['u_id']?></td>
                      <td style="color: <?=$v['status']!=1?'red':'';?>"><?=$this->enum_field->get_values ( 'is_status' )[$v['status']]?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs layui-btn-normal" href="<?=base_url("admin/goods_sku/index?spu_id=".$v['id']); ?>">规格</a>
                          <button style="display: <?=$v['status']?'none':''?>"  data-url="<?=base_url("admin/goods/to_examine/{$v['id']}"); ?>"  class="layui-btn layui-btn-xs confirm_get">提交审核</button>
                          <a style="display: <?=$v['status']==1?'none':'';?>;" class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods/edit/{$v['id']}"); ?>">编辑</a>
                          <button data-url="<?php echo base_url ( 'admin/goods/delete' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>
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
