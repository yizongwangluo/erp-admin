<?php $this->load->view('admin/common/header') ?>
<?php $this->load->view('admin/common/menu') ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">企业主体列表</li>
        <li class=""><a href='<?php echo base_url('admin/company/add') ?>'>新增企业主体</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline">
                        <select name="a" lay-verify="required">
                            <option value="0" selected>企业主体</option>
                            <option value="1" <?= $this->input->get('a') ? 'selected' : ''; ?>>企业账号</option>
                        </select>
                    </div>
                    <div class="layui-inline  col-xs-2">
                        <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>"
                               class="layui-input" placeholder="请输入查询关键词"/>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索</button>
                    <button class="layui-btn layui-btn-normal" type="button" id="daochu_all">导出主体</button>
                    <button class="layui-btn layui-btn-normal" type="button" id="daochu_zh">导出账号</button>
                </div>
                <div style='overflow:auto'>
                    <table class="layui-table" style='white-space: nowrap'>
                        <thead>
                        <tr>
                            <td>ID</td>
                            <td>公司名称
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=company_name&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=company_name&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>代理商
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=agent&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=agent&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>网站域名</td>
                            <td>开户状态
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=account_status&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=account_status&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>下户时间
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=logout_time&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=logout_time&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>账户数量
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=account_num&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=account_num&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>不限额数量
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=unlimit_num&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=unlimit_num&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>所属人
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=user_name&sort=asc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=user_name&sort=desc&search=<?php echo $this->input->get('search'); ?>'><i
                            class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                            </td>
                            <td>备注</td>
                            <td>操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($data)) { ?>
                            <?php foreach ($data as $v): ?>
                                <tr>
                                    <td><?= $v['id'] ?></td>
                                    <td><?= $v['company_name'] ?></td>
                                    <td><?= $v['agent'] ?></td>
                                    <td><?= $v['domain'] ?></td>
                                    <td>
                                        <?php
                                        if ($v['account_status'] == 0) {
                                            echo "审核成功";
                                        } else if ($v['account_status'] == 1) {
                                            echo "审核中";
                                        } else {
                                            echo "审核失败";
                                        }
                                        ?>
                                    </td>
                                    <td><?= date('Y-m-d', $v['logout_time']) ?></td>
                                    <td><?= $v['account_num'] ?></td>
                                    <td><?= $v['unlimit_num'] ?></td>
                                    <td><?= $v['user_name'] ?></td>
                                    <td><?= $v['company_remark'] ?></td>
                                    <td>
                                        <button class="layui-btn-xs layui-btn layui-btn-normal" type="button"
                                                data-modal="<?php echo base_url('admin/company/detail/' . $v['id']) ?>"
                                                data-title="企业主体详情" data-width="650px">详情
                                        </button>
                                        <a href='<?php echo base_url('admin/company/edit/' . $v['id']) ?>'>
                                            <button type="button" class="layui-btn layui-btn-xs"><i
                                                    class="layui-icon"></i></button>
                                        </a>
                                        <button data-url="<?php echo base_url('admin/company/del') ?>"
                                                data-id="<?= $v['id'] ?>" type="button"
                                                class="layui-btn layui-btn-danger layui-btn-xs confirm_post"><i
                                                class="layui-icon"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
<script>

    layui.use('form', function(){
        var form = layui.form
            ,layer = layui.layer;

        $('#daochu_all').click(function(){
            var index = layer.load();
            $(this).addClass('layui-btn-disabled').attr('disabled','');
            window.location.href = "/admin/company/daochu_all";
            layer.close(index);
        });

        $('#daochu_zh').click(function(){
            var index = layer.load();
            $(this).addClass('layui-btn-disabled').attr('disabled','');
            window.location.href = "/admin/companyaccount/daochu";
            layer.close(index);
        });


    });
</script>
<?php $this->load->view('admin/common/footer') ?>
