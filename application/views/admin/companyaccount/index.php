<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">企业账号列表</li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/add' ) ?>'>新增企业账号</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/lists' ) ?>'>申请列表</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/unreviewed' ) ?>'>待审批</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/rejected' ) ?>'>已驳回</a></li>
        <li><a href='<?php echo base_url ( 'admin/companyaccount/reviewed' ) ?>'>已完成</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
    <div class="layui-form">
        <div class="layui-inline  col-xs-2">
            <input type="text" name="search" value="<?php echo $this->input->get ( 'search' ); ?>"
                   class="layui-input" placeholder="请输入查询关键词"/>
        </div>
        <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
    </div>
                <div style='overflow:auto'>
                    <table class="layui-table" lay-size="sm" style='white-space: nowrap'>
        <thead>
        <tr>
            <td>ID</td>
            <td>企业账户ID</td>
            <td>网站域名</td>
            <td>所属企业主体
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=company_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=company_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>是否解限
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=isunlock&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=isunlock&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>账户状态
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=status&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=status&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>所属人
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=real_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=real_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>备注</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data)){ ?>
            <?php foreach ($data as $v): ?>
                <tr>
                    <td><?=$v['id']?></td>
                    <td><?=$v['company_account_id']?></td>
                    <td><?=$v['domain']?></td>
                    <td><?=$v['company_name']?></td>
                    <td><?=$v['isunlock']?></td>
                    <td>
                        <?php
                        if($v['status'] == 0){
                            echo "正常";
                        }else if($v['status'] == 1){
                            echo "封户";
                        }else{
                            echo "申诉中";
                        }
                        ?>
                    </td>
                    <td><?=$v['real_name']?></td>
                    <td><?=$v['companyaccount_remark']?></td>
                    <td>
                        <a href='<?php echo base_url ( 'admin/companyaccount/edit/'.$v['id'] ) ?>'><button type="button" class="layui-btn layui-btn-sm"><i class="layui-icon"></i></button></a>
                        <button data-url="<?php echo base_url ( 'admin/companyaccount/del' ) ?>" data-id="<?= $v['id'] ?>" type="button" class="layui-btn layui-btn-danger layui-btn-sm confirm_post"><i class="layui-icon"></i></button>
                    </td>
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
