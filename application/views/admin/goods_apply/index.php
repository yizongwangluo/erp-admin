<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">申请列表</li>
        <li><a href="<?php echo base_url ( 'admin/goods_apply/add' ) ?>">申请</a></li>
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
                    <td class="img">产品图片</td>
                    <td>产品名</td>
                    <td>SKU</td>
                    <td>规格</td>
                    <td>采购价（元）</td>
                    <td>重量（克）</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if($data){ foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td class="img"><a href="<?=base_url($v['img'])?>" target="_blank"><img src="<?=base_url($v['img'])?>"></a></td>
                      <td><?=$v['name']?></td>
                      <td><?=$v['sku_code']?></td>
                      <td><?=$v['norms']?></td>
                      <td><?=$v['price']?></td>
                      <td><?=$v['weight']?></td>
                      <td style="color: <?=$v['status']==2?'red':'';?>"><?=$this->enum_field->get_values ( 'is_status' )[$v['status']]?></td>
                      <td>
                          <button style="display: <?=$v['status']!=1?'none':'';?>;" data-url="<?php echo base_url ( 'admin/goods/synchronization' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs confirm_post layui-btn-warm">同步到主表</button>
                          <a style="display: <?=$v['status']==1?'none':'';?>;" class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods_apply/edit/{$v['id']}"); ?>">编辑</a>
                          <button style="display: <?=$v['status']==1?'none':'';?>;" data-url="<?php echo base_url ( 'admin/goods_apply/delete' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>
                      </td>
                  </tr>
                <?php endforeach;

                }
                ?>
                </tbody>
            </table>
        <div class="admin-page">
            <?php echo $page_html; ?>
        </div>
    </div>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>
