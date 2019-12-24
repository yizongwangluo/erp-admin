<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">提成列表</li>
    </ul>
    <div class="layui-tab-content">
            <form action="?" method="get">
                <div class="layui-form">
                    日期：
                    <div class="layui-inline">
                        <input type="text" name="datetime" value="<?=$where['datetime']?>" class="layui-input" id="test3" >
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
            </form>
            <table class="layui-table">
              <thead>
                <tr>
                    <td>UID</td>
                    <td>员工账号</td>
                    <td>营业额($)</td>
                    <td>广告费($)</td>
                    <td>产品总成本(¥)</td>
                    <td>订单数</td>
                    <td>毛利(¥)</td>
                    <td>毛利率</td>
                    <td>提成金额(¥)</td>
                    <td>备注</td>
                </tr>
                </thead>
                <tbody>
                <?php if($data){ foreach ($data as $v): ?>
                  <tr>
                      <td><?=$v['u_id']?></td>
                      <td><?=$v['user_name']?></td>
                      <td><?=$v['turnover']?></td>
                      <td><?=$v['ad_cost']?></td>
                      <td><?=$v['product_total_cost']?></td>
                      <td><?=$v['paid_orders']?></td>
                      <td><?=$v['gross_profit_rmb']?></td>
                      <td><?=$v['gross_profit_rate']?></td>
                      <td><?=$v['money']?></td>
                      <td><?=$v['remarks']?></td>
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
<script>

    layui.use('laydate', function() {
        var laydate = layui.laydate;

        //年月选择器
        laydate.render({
            elem: '#test3'
            ,type: 'month'
        });
    });

</script>

<?php $this->load->view ( 'admin/common/footer' ) ?>
