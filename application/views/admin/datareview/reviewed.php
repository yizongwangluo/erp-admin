<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href='<?php echo base_url ( 'admin/datareview/index' ) ?>'>全部</a></li>
        <li><a href='<?php echo base_url ( 'admin/datareview/unreviewed' ) ?>'>待审核</a></li>
        <li class="layui-this">已审核</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline  col-xs-2">
                        <input type="text" name="search" value="<?php echo $this->input->get ( 'search' ); ?>"
                               class="layui-input" placeholder="请输入查询关键词"/>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input class="layui-input date-time" name="start_time" placeholder="开始时间" value="<?php echo input('start_time'); ?>">
                        </div>
                        <div class="layui-input-line">-</div>
                        <div class="layui-input-inline">
                            <input class="layui-input date-time" value="<?php echo input('end_time'); ?>" name="end_time" placeholder="截止时间">
                        </div>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
                <div style='overflow:auto'>
                <table class="layui-table"  style='white-space: nowrap'>
                    <thead>
                    <tr>
                        <td>日期
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=datetime&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=datetime&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>店铺域名</td>
                        <td>店铺负责人
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=user_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=user_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>付款订单数
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=paid_orders&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=paid_orders&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>广告费用
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=ad_cost&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=ad_cost&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>营业额
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=turnover&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=turnover&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>销售ROI
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=ROI&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=ROI&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>每单广告成本
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=unit_ad_cost&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=unit_ad_cost&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>客单价
                            <span class="layui-table-sort layui-inline">
                    <a href='reviewed?title=unit_price&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='reviewed?title=unit_price&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>毛利</td>
                        <td>毛利率</td>
                        <td>审核状态</td>
                        <td>审核人</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){ ?>
                        <?php foreach ($data as $v): ?>
                            <tr>
                                <td><?=$v['datetime']?></td>
                                <td><?=$v['domain']?></td>
                                <td><?=$v['user_name']?></td>
                                <td><?=$v['paid_orders']?></td>
                                <?php if($v['ad_cost'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['ad_cost']."</td>";} ?>
                                <td><?=$v['turnover']?></td>
                                <?php if($v['ROI'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['ROI']."</td>";} ?>
                                <?php if($v['unit_ad_cost'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['unit_ad_cost']."</td>";} ?>
                                <td><?=$v['unit_price']?></td>
                                <?php if($v['gross_profit'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['gross_profit']."</td>";} ?>
                                <?php if($v['gross_profit_rate'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['gross_profit_rate']."</td>";} ?>

                                <?php if($v['review_status'] == 0){ ?>
                                    <td style="color: red">未上传</td>
                                <?php }elseif($v['review_status'] == 1){ ?>
                                    <td style="color: #1890ff">待审核</td>
                                <?php }else{ ?>
                                    <td>已审核</td>
                                <?php } ?>
                                <td><?=$v['reviewer_name']?></td>
                                <?php if($v['review_status'] == 2){ ?>
                                    <td><button class="layui-btn-xs layui-btn" type="button"  data-modal="<?php echo base_url ( 'admin/datareview/edit/'.$v['id'].'?url=reviewed' ) ?>"  data-title="审核广告费用" data-width="450px">修改</button></td>
                                <?php }else{ ?>
                                    <td><button class="layui-btn-xs layui-btn layui-btn-normal" type="button"  data-modal="<?php echo base_url ( 'admin/datareview/edit/'.$v['id'].'?url=reviewed' ) ?>"  data-title="审核广告费用" data-width="450px">审核</button></td>
                                <?php } ?>
                            </tr>
                        <?php endforeach;?>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
            </form>
            <div class="admin-page">
                <?php echo $page_html; ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>
<script type="text/javascript">

    layui.use('laydate', function() {
        var laydate = layui.laydate;
        //同时绑定多个
        lay('.date-time').each(function () {
            laydate.render({
                elem: this
                , trigger: 'click'
            });
        });
    });
</script>
