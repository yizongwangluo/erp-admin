<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>
<div class="main clear">
    <div class="tool">
        <form action="?" method="get">
            <input type="text" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="txt"  placeholder="请输入表单名称" />
        </form>
        <a href="javascript:$('form').submit()" class="button">搜索</a>
        <a href="<?php echo site_url('admin/form/addoption/' . $fid);?>" class="button">新增选项</a>
    </div>
    <div class="table02">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th width="5%">ID</th>
                <th width="10%">选项名称</th>
                <th width="10%">是否必填</th>
                <th width="10%">排序</th>
                <th width="10%">操作</th>
            </tr>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?=$item['id']; ?></td>
                <td><?=$item['title'];?></td>
                <td><?=$item['ismust'] == 1 ? '是' : '否';?></td>
                <td><?=$item['orderid'];?></td>
                <td>
                    <a href="<?=base_url("admin/form/editoption/{$item['id']}"); ?>">编辑</a>|
                    <a href="javascript:ajax_operate('<?php echo base_url("admin/form/deleteoption/{$item['id']}"); ?>')">删除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php echo $page_html; ?>

</div>

<?php $this->load->view('admin/common/footer')?>
