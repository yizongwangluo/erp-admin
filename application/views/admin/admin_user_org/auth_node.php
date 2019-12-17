<link rel="stylesheet" href="/static/admin/css/ztree-metro-style.css">
<div class="layui-tab admin-layui-tab layui-tab-brief">
	<div class="layui-tab-content">
		<div class="layui-tab-item layui-show">
            <ul id="tree" class="ztree"></ul>
		</div>
        <input type="hidden" id="group_id" name="id" value="<?=$id?>">
        <button class="layui-btn" id="auth-btn">确定</button>
    </div>
</div>
<script src="/static/admin/js/jquery.ztree.all.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        /**
         * 加载树形授权菜单
         */
        var _id = $("#group_id").val();
        var tree = $("#tree");
        var zTree;

        // zTree 配置项
        var setting = {
            check: {
                enable: true,
                chkboxType:  { "Y": "s", "N": "s" }  //“p” 表示操作会影响父级节点；“s” 表示操作会影响子级节点。
            },
            view: {
                dblClickExpand: true,
                showLine: true,
                showIcon: false,
                selectedMulti: false
            },
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "pid",
                    rootpid: ""
                },
                key: {
                    name: "name"
                }
            }
        };

        $.ajax({
            url: "<?=site_url ('admin/admin_user_org/getjson')?>",
            type: "post",
            dataType: "json",
            cache: false,
            data: {
                id: _id
            },
            success: function (data) {
                zTree = $.fn.zTree.init(tree, setting, data);
            }
        });

        /**
         * 授权提交
         */
        $("#auth-btn").on("click", function () {
            var checked_ids,auth_rule_ids = [];
            checked_ids = zTree.getCheckedNodes(); // 获取当前选中的checkbox
            $.each(checked_ids, function (index, item) {
                auth_rule_ids.push(item.id);
            });
            $.ajax({
                url: "<?=site_url ('admin/admin_user_org/updateauthgrouprule')?>",
                type: "post",
                cache: false,
                data: {
                    id: _id,
                    auth_rule_ids: auth_rule_ids,
                },
                success: function (data) {
                    layer.msg(data.msg);
                }
            });
        });
    });
</script>