<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href='<?php echo base_url ( 'admin/datareview/index' ) ?>'>全部</a></li>
        <li class="layui-this">待审核</li>
        <li><a href='<?php echo base_url ( 'admin/datareview/reviewed' ) ?>'>已审核</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline  col-xs-2">
                        <select name="user" lay-search="">
                            <option value="">直接选择或搜索选择</option>
                            <?php foreach ($users as $v): ?>
                                <option value="<?=$v['s_user_name']?>" <?= $v['s_user_name'] == $this->input->get ( 'user' ) ? selected : '' ?> ><?=$v['s_user_name']?></option>
                            <?php endforeach;?>
                        </select>
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
                    <div class="layui-inline  col-xs-2">
                        <input type="text" name="search" value="<?php echo $this->input->get ( 'search' ); ?>"
                               class="layui-input" placeholder="请输入查询关键词"/>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
                <div style='overflow:auto'>
                    <table class="layui-table"  style='white-space: nowrap'>
                        <thead>
                        <tr>
                            <td>日期
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=datetime&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=datetime&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>店铺域名</td>
                            <td>店铺负责人
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=user_name&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=user_name&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>营业额($)
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=turnover&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=turnover&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>付款订单数
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=paid_orders&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=paid_orders&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>广告费用($)
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=ad_cost&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=ad_cost&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>手续费($)
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=formalities_cost&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=formalities_cost&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>产品总成本(¥)
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=product_total_cost&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=product_total_cost&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>毛利($)</td>
                            <td>毛利(¥)</td>
                            <td>毛利率</td>
                            <td>ROI
                                <span class="layui-table-sort layui-inline">
                    <a href='index?title=ROI&sort=asc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=ROI&sort=desc&user=<?php echo $this->input->get ( 'user' ); ?>&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>备注</td>
                            <td>审核状态</td>
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
                                <td><?=$v['turnover']?></td>
                                <td><?=$v['paid_orders']?></td>
                                <?php if($v['ad_cost'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['ad_cost']."</td>";} ?>
                                <td><?=$v['formalities_cost']?></td>
                                <td><?=$v['product_total_cost']?></td>
                                <?php if($v['gross_profit'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['gross_profit']."</td>";} ?>
                                <?php if($v['gross_profit_rmb'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['gross_profit_rmb']."</td>";} ?>
                                <?php if($v['gross_profit_rate'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['gross_profit_rate']."</td>";} ?>
                                <?php if($v['ROI'] == null){ ?>
                                    <td style="color: red">数据未上传</td>
                                <?php }else{echo "<td>".$v['ROI']."</td>";} ?>
                                <td><?=$v['operate_remark']?></td>
                                <?php if($v['review_status'] == 0){ ?>
                                    <td style="color: red">未上传</td>
                                <?php }elseif($v['review_status'] == 1){ ?>
                                    <td style="color: #1890ff">待审核</td>
                                <?php }else{ ?>
                                    <td>已审核</td>
                                <?php } ?>


                                <?php if($v['review_status'] == 2){ ?>
                                    <td>
                                        <a class="layui-btn-xs layui-btn layui-btn-danger" type="button"  href="<?php echo base_url ( 'admin/operate/detail/'.$v['id'] ) ?>">详情</a>
                                        <button class="layui-btn-xs layui-btn" type="button"  data-modal="<?php echo base_url ( 'admin/datareview/edit/'.$v['id'].'?url=unreviewed' ) ?>"  data-title="审核广告费用" data-width="450px">修改</button>
                                    </td>
                                <?php }else{ ?>
                                    <td>
                                        <a class="layui-btn-xs layui-btn layui-btn-danger" type="button"  href="<?php echo base_url ( 'admin/operate/detail/'.$v['id'] ) ?>">详情</a>
                                        <button class="layui-btn-xs layui-btn layui-btn-normal" type="button"  data-modal="<?php echo base_url ( 'admin/datareview/edit/'.$v['id'].'?url=unreviewed' ) ?>"  data-title="审核广告费用" data-width="450px">审核</button>
                                    </td>
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
