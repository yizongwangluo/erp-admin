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
                    <div class="layui-inline">
                        <select name="status" lay-verify="required">
                            <option value="">请选择状态</option>
                            <?php foreach($this->enum_field->get_values('is_status') as $key=>$value){ ?>
                                <option value="<?php echo $key ?>" <?php if(is_numeric($where['status']) && $where['status']==$key){ echo 'selected'; } ?> ><?php echo $value ?></option>
                            <?php   } ?>
                        </select>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
            </form>
        <div style='overflow:auto'>
            <table class="layui-table"  style='white-space: nowrap'>
              <thead>
                <tr>
                    <td>ID</td>
                    <td class="img">产品图片</td>
                    <td>产品名</td>
                    <td>SKU</td>
                    <td>SKU别名</td>
                    <td>规格名1</td>
                    <td>规格值1</td>
                    <td>规格名2</td>
                    <td>规格值2</td>
                    <td>采购价（元）</td>
                    <td>重量（克）</td>
                    <td>用户名</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php if($data){ foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td class="img"><a href="<?=$v['img']?>" target="_blank"><img src="<?=$v['img']?>"></a></td>
                      <td><?=$v['name']?></td>
                      <td><?=$v['sku_code']?></td>
                      <td><?=$v['alias']?></td>
                      <td><?=$v['norms_name']?></td>
                      <td><?=$v['norms']?></td>
                      <td><?=$v['norms_name1']?></td>
                      <td><?=$v['norms1']?></td>
                      <td><?=$v['price']?></td>
                      <td><?=$v['weight']?></td>
                      <td><?=$v['user_name']?></td>
                      <td style="color: <?php if($v['status']==3){echo "red";}elseif($v['status']==2){echo "#1890ff";}?>"><?=$this->enum_field->get_values ( 'is_status' )[$v['status']]?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/goods_apply/info/{$v['id']}"); ?>">查看</a>
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
            </div>
        <div class="admin-page">
            <?php echo $page_html; ?>
        </div>
    </div>
</div>

<?php $this->load->view ( 'admin/common/footer' ) ?>
