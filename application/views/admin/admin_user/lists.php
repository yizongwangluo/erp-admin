<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>

<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">账户列表</li>
        <li><a href="<?php echo base_url ( 'admin/admin_user/add' ) ?>">新增账户</a></li>
    </ul>
    <div class="layui-tab-content">
        <form action="?" method="get" >
            <div class="layui-form">
                <div class="layui-inline">
                    <input type="text" name="name" value="<?=$where['name']?>"
                           class="layui-input" placeholder="输入搜索条件"/>
                </div>
                <div class="layui-inline">
                    <select name="is_disable" >
                        <option value="" >--状态--</option>
                        <option value="0" <?php if($this->input->get ( 'is_disable' )==0 && is_numeric($this->input->get ( 'is_disable' ))){ echo 'selected'; } ?>>正常</option>
                        <option value="1" <?php if($this->input->get ( 'is_disable' )==1){ echo 'selected'; } ?>>禁用</option>
                    </select>
                </div>
                <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索</button>
            </div>
        </form>
        <div class="layui-tab-item layui-show">
            <table class="layui-table">
                <thead>
                <th>ID</th>
                <th>工号</th>
                <th>用户名</th>
                <th>真实姓名</th>
                <th>权限组</th>
                <th>最后登陆时间</th>
                <th>是否禁用</th>
                <th>操作</th>
                </thead>
                <tbody>
                <?php foreach ($data as $item): ?>
                <tr>
                    <td><?=$item['id'];?></td>
                    <td><?=$item['job_number'];?></td>
                    <td><?=$item['user_name'];?></td>
                    <td><?=$item['real_name'];?></td>
                    <td><?=$item['title'];?></td>
                    <td><?=$item['login_time']?date ('Y-m-d H:i:s',$item['login_time']):''?></td>
                    <td><?=$item['is_disable'] == 1? '是':'否'?></td>
                    <td>
                        <a class="layui-btn-mini layui-btn" type="button" href="<?php echo base_url ( 'admin/admin_user/edit/'.$item['id'] ) ?>">编辑</a>
                        <button data-modal="<?php echo base_url ( 'admin/admin_user_org/auth_node/'.$item['id']  ) ?>" data-title="授权" data-width="450px" class="layui-btn layui-btn-mini layui-btn-normal">授权</button>
                        <button class="layui-btn layui-btn-mini layui-btn-danger confirm_get" data-url="<?=base_url ('admin/admin_user/del/'.$item['id'])?>" >删除</button>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="admin-page">
	<?php echo $page_html; ?>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>

