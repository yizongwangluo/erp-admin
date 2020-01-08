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
        <li><a href="<?php echo base_url ( 'admin/goods/addexcel' ) ?>">导入</a></li>
    </ul>
    <div class="layui-tab-content">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline">
                        <select name="category_id" lay-verify="required" lay-search>
                            <option value="">请选择类别</option>
                            <?php foreach($category_list as $key=>$value){ ?>
                                <option value="<?php echo $value['id'] ?>" <?php if($value['id']==$where['category_id']){ echo 'selected'; } ?> <?=$value['status']==2?'disabled':''?> ><?php echo $value['name'] ?></option>
                            <?php   } ?>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input type="text" name="name" value="<?=$where['name']?>"
                               class="layui-input" placeholder="输入产品名"/>
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
                    <td>同步（通途）</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td class="img"><a href="<?=base_url($v['img'])?>" target="_blank"><img src="<?=base_url($v['img'])?>"></a></td>
                      <td><?=$v['name']?></td>
                      <td><?=$v['sku_code']?></td>
                      <td><?=$v['norms']?></td>
                      <td><?=$v['price']?></td>
                      <td><?=$v['weight']?></td>
                      <td style="color: <?=$v['is_tongtu']!=1?'red':'';?>"><?=$v['is_tongtu']?'已同步':'未同步'?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods/info/{$v['id']}"); ?>">查看</a>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods/edit/{$v['id']}"); ?>">编辑</a>
                          <button data-url="<?php echo base_url ( 'admin/goods/add_sku_tongtu' ) ?>" data-id="<?= $v['id'] ?>" class="layui-btn layui-btn-xs confirm_post layui-btn-warm">同步到通途</button>
                         <!-- <button style="display: <?/*=$v['status']==1?'none':'';*/?>;" data-url="<?php /*echo base_url ( 'admin/goods/delete' ) */?>" data-id="<?/*= $v['id'] */?>" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>-->
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
