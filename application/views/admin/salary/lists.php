<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline  col-xs-2">
                            <select name="search" lay-search="">
                                <option value="">直接选择或搜索选择</option>
                                <?php foreach ($users as $v): ?>
                                    <option value="<?=$v['s_real_name']?>" <?= $v['s_real_name'] == $this->input->get ( 'search' ) ? selected : '' ?> ><?=$v['s_real_name']?></option>
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
                    <div class="layui-inline  col-xs-1" style="padding-left: 10px">
                        <select name="status">
                            <option value="">全部</option>
                                <option value = 0 <?= $this->input->get ( 'status' ) == '0' ? selected : '' ?>>未发放</option>
                                <option value = 1 <?= $this->input->get ( 'status' ) == '1' ? selected : '' ?>>已发放</option>
                        </select>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索</button>
                    <button class="layui-btn-normal layui-btn" type="button" id="payroll" >发放工资</button>
                </div>
                <div style='overflow:auto'>
                    <table class="layui-table"  style='white-space: nowrap'>
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="all"></th>
                            <th>日期
                                <span class="layui-table-sort layui-inline">
                    <a href='lists?title=date&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=date&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </th>
                            <th>员工
                                <span class="layui-table-sort layui-inline">
                    <a href='lists?title=real_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=real_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </th>
                            <th>底薪
                                <span class="layui-table-sort layui-inline">
                    <a href='lists?title=basic_salary&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=basic_salary&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </th>
                            <th>提成
                                <span class="layui-table-sort layui-inline">
                    <a href='lists?title=commission&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=commission&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </th>
                            <th>合计
                                <span class="layui-table-sort layui-inline">
                    <a href='lists?title=total&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=total&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </th>
                            <th>发放状态
                                <span class="layui-table-sort layui-inline">
                    <a href='lists?title=salary_status&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='lists?title=salary_status&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>&start_time=<?php echo $this->input->get ( 'start_time' ); ?>&end_time=<?php echo $this->input->get ( 'end_time' ); ?>&status=<?php echo $this->input->get ( 'status' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($data)){ ?>
                            <?php foreach ($data as $v): ?>
                                <tr>
                                    <td><input type="checkbox" name="ckbx" id="<?= $v['id'] ?>"/></td>
                                    <td><?= $v['date'] ?></td>
                                    <td><?= $v['real_name']?></td>
                                    <td><?= $v['basic_salary']?></td>
                                    <td><?=$v['commission']?></td>
                                    <td><?=$v['total']?></td>
                                    <?php if($v['salary_status'] == 0){echo "<td style='color: red'>未发放</td>";}else{echo "<td style='color: #00a0e9'>已发放</td>";} ?>
                                    <td><?=$v['salary_remark']?></td>
                                    <?php if($v['salary_status'] == 0){ ?>
                                    <td><button class="layui-btn-xs layui-btn" type="button"  data-modal="<?php echo base_url ( 'admin/salary/edit/'.$v['id'] ) ?>"  data-title="编辑员工薪资" data-width="450px">编辑</button></td>
                                    <?php }else{ ?>
                                    <td>已发放</td>
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
                ,type: 'month'
                , trigger: 'click'
            });
        });
    });

    window.onload = function() {
        var btn = document.getElementById("all");
        btn.onclick = function() {
            var flag = this.checked;
            var items = document.getElementsByName("ckbx");
            for (var i = 0; i < items.length; i++) {
                items[i].checked = flag;//将所有item的状态设为全选按钮的状态
            }
        }

        var items = document.getElementsByName("ckbx");
        for (var i = 0; i < items.length; i++) {
            items[i].onclick = function() {//对每个item设置点击
                var number = 0;//记录选中的个数
                for (var j = 0; j < items.length; j++) {
                    if (items[j].checked) {
                        number++;
                    }
                }
                document.getElementById("all").checked = (items.length == number);
            }
        }

        var pay = document.getElementById("payroll");
        pay.onclick = function() {
            var items = document.getElementsByName("ckbx");
            var ids = new Array();
            for (var i = 0; i < items.length; i++) {
                if(items[i].checked)
                    ids.push(items[i].id);
            }

            $.post('/admin/salary/payroll', {ids:ids}, function (response) {
                if (!response.status) {
                    layer.msg(response.msg, {time: 2000, icon: 6});
                    layer.close(index);
                    return false;
                } else {
                    layer.msg('发放成功', {time: 2000, icon: 6}, function () {
                        window.location = '/admin/salary/lists';
                    })
                }
            },'json');

        }
    }


</script>

