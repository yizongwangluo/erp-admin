<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li><a href='<?php echo base_url ( 'admin/apply/add' ) ?>'>账号申请/变更</a></li>
        <li class="layui-this">我的申请</li>
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
                    <div class="layui-inline  col-xs-1" style="padding-left: 10px">
                        <select name="apply_status">
                            <option value="">全部</option>
                            <option value = 0 <?= $this->input->get ( 'apply_status' ) == '0' ? selected : '' ?>>待审批</option>
                            <option value = 1 <?= $this->input->get ( 'apply_status' ) == '1' ? selected : '' ?>>已驳回</option>
                            <option value = 2 <?= $this->input->get ( 'apply_status' ) == '2' ? selected : '' ?>>已完成</option>
                        </select>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
                <div style='overflow:auto'>
                    <table class="layui-table"  style='white-space: nowrap'>
        <thead>
        <tr>
            <td>日期
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=date&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=date&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>申请类型
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=type&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=type&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>账号类型
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=account_type&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=account_type&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>申请概要</td>
            <td>申请人
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=user_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=user_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>备注</td>
            <td>审批状态
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=apply_status&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=apply_status&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&apply_status=<?php echo $this->input->get ( 'apply_status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>批注</td>
            <td>审核人</td>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data)){ ?>
            <?php foreach ($data as $v): ?>
                <tr>
                    <td><?=date('Y-m-d',$v['date'])?></td>
                    <td><?= $v['type'] == 0 ? 新账号 : 旧账号?></td>
                    <?php
                    if($v['account_type'] == 0){
                        echo "<td>店铺</td>";
                    }else if($v['account_type'] == 1){
                        echo "<td>企业账号</td>";
                    }else{
                        echo "<td>个人账号</td>";
                    }
                    ?>
                    <td><?=$v['apply_summary']?></td>
                    <td><?=$v['user_name']?></td>
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
                    <td><?= $v['apply_status'] == 0 ? '' : $v['reviewer_name'] ?></td>
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

