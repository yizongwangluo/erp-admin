<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
                <div class="layui-form">
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
                    <a href='mylist?title=date&sort=asc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='mylist?title=date&sort=desc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>员工
                                <span class="layui-table-sort layui-inline">
                    <a href='mylist?title=user_name&sort=asc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='mylist?title=user_name&sort=desc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>底薪
                                <span class="layui-table-sort layui-inline">
                    <a href='mylist?title=basic_salary&sort=asc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='mylist?title=basic_salary&sort=desc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>提成
                                <span class="layui-table-sort layui-inline">
                    <a href='mylist?title=commission&sort=asc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='mylist?title=commission&sort=desc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>合计
                                <span class="layui-table-sort layui-inline">
                    <a href='mylist?title=total&sort=asc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='mylist?title=total&sort=desc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>发放状态
                                <span class="layui-table-sort layui-inline">
                    <a href='mylist?title=salary_status&sort=asc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='mylist?title=salary_status&sort=desc&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>备注</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($data)){ ?>
                            <?php foreach ($data as $v): ?>
                                <tr>
                                    <td><?= $v['date'] ?></td>
                                    <td><?= $v['user_name']?></td>
                                    <td><?= $v['basic_salary']?></td>
                                    <td><?=$v['commission']?></td>
                                    <td><?=$v['total']?></td>
                                    <?php if($v['salary_status'] == 0){echo "<td style='color: red'>未发放</td>";}else{echo "<td style='color: #00a0e9'>已发放</td>";} ?>
                                    <td><?=$v['salary_remark']?></td>
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
                ,type: 'month'
                , trigger: 'click'
            });
        });
    });
</script>

