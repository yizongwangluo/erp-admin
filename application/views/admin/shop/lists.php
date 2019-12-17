<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href='<?php echo base_url ( 'admin/shop/index' ) ?>'>店铺列表</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/add' ) ?>'>新增店铺</a></li>
        <li class="layui-this">申请列表</li>
        <li><a href='<?php echo base_url ( 'admin/shop/unreviewed' ) ?>'>待审批</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/rejected' ) ?>'>已驳回</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/reviewed' ) ?>'>已完成</a></li>
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
                    <a href='lists?title=date&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=date&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>申请类型
                            <span class="layui-table-sort layui-inline">
                    <a href='lists?title=type&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=type&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>申请概要</td>
                        <td>申请人
                            <span class="layui-table-sort layui-inline">
                    <a href='lists?title=real_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=real_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>备注</td>
                        <td>审批状态
                            <span class="layui-table-sort layui-inline">
                    <a href='lists?title=apply_status&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=apply_status&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>批注</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){ ?>
                        <?php foreach ($data as $v): ?>
                            <tr>
                                <td><?=date('Y-m-d',$v['date'])?></td>
                                <td><?= $v['type'] == 0 ? 新账号 : 旧账号?></td>
                                <td><?=$v['apply_summary']?></td>
                                <td><?=$v['real_name']?></td>
                                <td><?=$v['apply_remark']?></td>
                                <?php
                                if($v['apply_status'] == 0){
                                    echo "<td style='color: #1890ff'>待审批</td>";
                                }else if($v['apply_status'] == 1){
                                    echo "<td style='color: red'>已驳回</td>";
                                }else{
                                    echo "<td>已完成</td>";
                                }
                                ?>
                                <td><?=$v['annotate']?></td>
                                <?php if($v['apply_status'] == 0){ ?>
                                    <td><button class="layui-btn-xs layui-btn layui-btn-normal" type="button"  data-modal="<?php echo base_url ( 'admin/shop/review_edit/'.$v['id'].'?url=lists' ) ?>"  data-title="账号审批" data-width="450px">审批</button></td>
                                <?php }else{ ?>
                                    <td>审核人：<?=$v['reviewer_name']?></td>
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

