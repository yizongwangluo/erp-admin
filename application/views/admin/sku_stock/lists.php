<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">我的申请</li>
        <li><a href="/admin/sku_stock/add">添加</a></li>
    </ul>
    <div class="layui-tab-content">
            <form action="?" method="get">
                <div class="layui-form">
                    <!--<div class="layui-inline  col-xs-3">
                        <input type="text" name="keyword" value="<?/*=$where['keyword']*/?>"
                               class="layui-input" placeholder="输入关键词"/>
                    </div>-->
                    <div class="layui-inline">
                        <select name="status" lay-verify="required">
                            <option value="">请选择</option>
                            <?php foreach($this->enum_field->get_values('sku_status') as $key=>$value){ ?>
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
                    <td>SKU编号</td>
                    <td>申请补货数</td>
                    <td>备货天数</td>
                    <td>运营备注</td>
                    <td>申请人</td>
                    <td>申请时间</td>
                    <td>审批人</td>
                    <td>审批时间</td>
                    <td>备注</td>
                    <td>状态</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$v['sku_id']?></td>
                      <td><?=$v['add_sku_number']?></td>
                      <td><?=$v['days']?></td>
                      <td><?=$v['remarks']?></td>
                      <td><?=$admin['user_name']?></td>
                      <td><?=date('Y-m-d H:i:s',$v['addtime']);?></td>
                      <td><?=$v['approval_u_id']?></td>
                      <td><?=$v['approval_time']?></td>
                      <td><?=$v['approval_remarks']?></td>
                      <td style="color: <?=$v['status']!=1?'red':'';?>"><?=$this->enum_field->get_values ( 'sku_status' )[$v['status']]?></td>
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
