<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<style>
    .img{
        /*width: 10%;word-wrap:break-word;word-break:break-all;*/
    }
    #daochu{
        cursor:pointer;
        border: 1px solid silver
    }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">订单列表</li>
    </ul>
    <div class="layui-tab-content">
              <form action="?" method="get" >
                <div class="layui-form">
                    <div class="layui-inline">
                        <select name="shop_id" lay-verify="required" lay-search>
                            <option value="">请选择店铺</option>
                            <?php foreach($shoplist as $key=>$value){ ?>
                                <option value="<?php echo $value['id'] ?>" <?php if($value['id']==$where['shop_id']){ echo 'selected'; } ?> ><?php echo $value['domain'] ?></option>
                            <?php   } ?>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input date-time" name="datetime" placeholder="时间" value="<?php echo input('datetime'); ?>">
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索</button>
                </div>
            </form>
        <div style='overflow:auto'>
            <table class="layui-table"  style='white-space: nowrap'>
              <thead>
                <tr>
                    <td>ID</td>
                    <td>shopfiy订单号</td>
                    <td>运单号</td>
                    <td>运费（￥）</td>
                    <td>总价（$）</td>
                    <td>原价</td>
                    <td>汇率</td>
                    <td>创建时间</td>
                    <td>修改时间</td>
                    <td>总重量 （g）</td>
                    <td>状态</td>
                    <td>店铺</td>
                    <td>日期</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['id']?></td>
                      <td><?=$v['shopify_o_id']?></td>
                      <td><?=$v['tracking_number']?></td>
                      <td><?=$v['freight']?$v['freight']:'<em style="color: red;">暂无</em>'?></td>
                      <td><?=$v['total_price_usd']?></td>
                      <td><?=$v['total_price']?>（<?=$v['price_currency']?>）</td>
                      <td><?=$v['rate']?></td>
                      <td><?=$v['created_at']?></td>
                      <td><?=$v['updated_at']?></td>
                      <td><?=$v['total_weight']?></td>
                      <td><?=$v['financial_status']?></td>
                      <td><?=$shoplist[$v['shop_id']]['domain']?></td>
                      <td><?=$v['datetime']?></td>
                      <td>
                          <a class="layui-btn layui-btn-xs" href="<?=base_url("admin/order/info/{$v['id']}"); ?>">查看</a>
                      </td>
                  </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            </div>
        <div class="admin-page">
            <?php echo $page_html; ?>
        </div>
    </div>
</div>
<script>
    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //同时绑定多个
        lay('.date-time').each(function () {
            laydate.render({
                elem: this
                ,type: 'date'
                , trigger: 'click'
            });
        });
    });
</script>
<?php $this->load->view ( 'admin/common/footer' ) ?>
