<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">提成规则列表</li>
        <li><a href="<?php echo base_url ( 'admin/royalty_rules/add' ) ?>">新增规则</a></li>
    </ul>
    <div class="layui-tab-content">
            <table class="layui-table">
              <thead>
                <tr>
                    <td>ID</td>
                    <td>部门</td>
                    <td>手续费</td>
                    <td>运费(g)</td>
                    <td>挂号费</td>
                    <td>备注</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$o_list[$v['o_id']]?></td>
                      <td><?=$v['service_charge']?></td>
                      <td><?=$v['freight']?></td>
                      <td><?=$v['register_fee']?></td>
                      <td><?=$v['remarks']?></td>
                      <td>
                          <a style="display: <?=$v['status']==1?'none':'';?>;" class="layui-btn layui-btn-xs" href="<?=base_url("admin/royalty_rules/edit/{$v['id']}"); ?>">编辑</a>
<!--                          <button data-url="--><?php //echo base_url ( 'admin/goods/delete' ) ?><!--" data-id="--><?//= $v['id'] ?><!--" class="layui-btn layui-btn-xs layui-btn-danger confirm_post">删除</button>-->
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
