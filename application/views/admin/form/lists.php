<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>
<div class="main clear">
    <div class="tool">
        <form action="?" method="get">
            <input type="text" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="txt txtFillet"  placeholder="请输入表单名称" />
        </form>
        <a href="javascript:$('form').submit()" class="button">搜索</a>
        <a href="<?php echo site_url('admin/form/add');?>" class="button">新增表单</a>
    </div>
    <div class="table02">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th width="5%">ID</th>
                <th width="20%">表单名称</th>
                <th width="5%">当前状态</th>
                <th width="10%">发布时间</th>
                <th width="15%">操作</th>
            </tr>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?=$item['id']; ?></td>
                <td><a href="<?=base_url("admin/form/submit/{$item['id']}"); ?>"><?=$item['fname'];?></a></td>
                <td><?=$item['display'] == 1 ? '启用' : '禁用';?></td>
                <td><?=date('Y-m-d H:i',$item['pubdate']); ?></td>
                <td>
                    <a href="<?=base_url("admin/form/contentlists/{$item['id']}"); ?>">查看内容</a>|
                    <a href="<?=base_url("admin/form/addoption/{$item['id']}"); ?>">新增选项</a>|
                    <a href="<?=base_url("admin/form/optionlists/{$item['id']}"); ?>">选项列表</a>|
                    <a href="javascript:ajax_operate('<?php echo base_url("admin/form/display/{$item['id']}"); ?>')"><?=$item['display'] == 0 ? '启用' : '禁用';?></a>|
                    <a href="<?=base_url("admin/form/edit/{$item['id']}"); ?>">编辑</a>|
                    <a href="javascript:ajax_operate('<?php echo base_url("admin/form/delete/{$item['id']}"); ?>')">删除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php echo $page_html; ?>

</div>

<?php $this->load->view('admin/common/footer')?>
