<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
    <div class="layui-form">
        <div class="layui-inline  col-xs-2">
            <input type="text" name="search" value="<?php echo $this->input->get ( 'search' ); ?>"
                   class="layui-input" placeholder="请输入查询关键词"/>
        </div>
        <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索</button>
    </div>
                <div style='overflow:auto'>
                    <table class="layui-table" lay-size="sm" style='white-space: nowrap'>
        <thead>
        <tr>
            <td>ID</td>
            <td>用户名</td>
            <td>密码</td>
            <td>RdoIp
                <span class="layui-table-sort layui-inline">
                <a href='index?title=RdoIp&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                <a href='index?title=RdoIp&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>Rdo用户名</td>
            <td>Rdo密码</td>
            <td>Rdo端口
                <span class="layui-table-sort layui-inline">
                    <a href='index?title=Rdo_port&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=Rdo_port&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>所属人
                <span class="layui-table-sort layui-inline">
                <a href='index?title=real_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                <a href='index?title=real_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
            </td>
            <td>Cookies</td>
            <td>备注</td>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data)){ ?>
            <?php foreach ($data as $v): ?>
                <tr>
                    <td><?=$v['id']?></td>
                    <td><?=$v['person_username']?></td>
                    <td><?=$v['person_password']?></td>
                    <td><?=$v['RdoIp']?></td>
                    <td><?=$v['Rdo_username']?></td>
                    <td><?=$v['Rdo_password']?></td>
                    <td><?=$v['Rdo_port']?></td>
                    <td><?=$v['real_name']?></td>
                    <td>
                        <button class="layui-btn layui-btn-xs layui-btn-normal" type="button" data-modal="<?php echo base_url ( 'admin/personaccount/cookies/'.$v['id'] ) ?>"  data-title="Cookies" data-width="450px">查看</button>
                        <textarea id="test<?=$v['id']?>" type="longtext" style="display: none;"><?=$v['cookies']?></textarea>
                        <button onclick="textCopy(document.getElementById('test<?=$v['id']?>').value)" class="layui-btn layui-btn-xs layui-btn-danger">复制</button>
                    </td>
                    <td><?=$v['person_remark']?></td>
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
<script>
    var textCopy=function (data) {
        var f=document.createElement("form");
        f.id="copy-"+Date.parse(new Date());
        f.onsubmit=function(){return false};
        f.style="opacity: 0;height: 1px;width: 1px;overflow: hidden;position:fixed;top: -1;left: -1;z-index: -1;"
        f.innerHTML=`<button onclick='story.select();document.execCommand("Copy");'></button>
                <textarea name="story">${data}</textarea>`;
        if(!data){
            alert("cookies为空!")
        }
        document.body.appendChild(f);
        document.querySelector(`#${f.id}>button`).click();
        document.body.removeChild(document.getElementById(f.id));
    }
</script>

