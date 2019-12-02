<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
    <div class="layui-tab admin-layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this">组织列表</li>
            <li class=""><a href="<?php echo base_url ( 'admin/admin_organization/add' ) ?>">新增岗位</a></li>
        </ul>
        <div class="layui-tab-content">
            <div id="test1" class="demo-tree demo-tree-box" style="width: 20%;min-height:600px ; height: 100%;float: left; overflow: scroll;"></div>

            <div class="layui-tab-item layui-show" style="float: left;width: 70%;margin-left: 30px;">

                <table class="layui-hide" id="test" lay-filter="test"></table>
            </div>

            <script type="text/html" id="barDemo">
                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            </script>
        </div>
    </div>


<script>

    layui.use(['form','tree','layer','table'], function(){
        var form = layui.form
            ,tree = layui.tree
            ,layer = layui.layer
            ,table = layui.table;
        //获取组织结构树
        function list(){
            $.get('/admin/admin_organization/lists',{},function(obj){
                if(!obj.status){
                    layer.msg(obj.msg, {time: 2000, icon: 6});
                }else{
                    tree.reload('demoId', {
                        data:[obj.data.list]
                    })
                }
            },'json')
        }

        list(); //初始化tree

        //渲染
        var inst1 = tree.render({
            elem: '#test1'  //绑定元素
            ,id: 'demoId' //定义索引
            ,data: []
            ,onlyIconControl: true  //是否仅允许节点左侧图标控制展开收缩
            ,click:function(obj){
                tableIns.reload({ //表格重载
                    where: {
                        o_id:obj.data.id
                    }
                });
            }
            ,edit: [ 'update', 'del']
            ,operate: function(obj){
                var type = obj.type; //得到操作类型：add、edit、del
                var data = obj.data; //得到当前节点的数据
                var elem = obj.elem; //得到当前节点元素
                //Ajax 操作
                var id = data.id; //得到节点索引
                if(type === 'update'){ //修改节点
                    window.location.href = '/admin/admin_organization/edit/'+id;
                } else if(type === 'del'){ //删除节点

                    $.get('/admin/admin_organization/del/'+id,{},function(obj){
                        if(!obj.status){
                            layer.msg(obj.msg, {time: 2000, icon: 6});
                            //重载tree实例
                            list();
                        }else{
                            layer.msg('删除成功！', {time: 2000, icon: 6});
                        }
                    },'json')
                }else if(type==='update2'){
                    console.log(123);
                }
            }
        });

        //用户列表渲染
        var tableIns = table.render({
            elem: '#test'
            ,url:'/admin/admin_user/lists_org_id'
            ,where:{o_id:1}
            ,cols: [[
                {field:'id', title: 'ID'}
                ,{field:'user_name', title: '用户名'}
                ,{field:'real_name', title: '性别'}
                ,{field:'is_disable', title: '是否禁用', templet: function(res){
                    return res.is_disable==1?'是':'否';
                }}
                ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
            ]]
        });

        //监听行工具事件
        table.on('tool(test)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                window.location.href = '/admin/admin_user/lists?id='+data.id;
            }
        });

    });



</script>

<?php $this->load->view ( 'admin/common/footer' ) ?>

