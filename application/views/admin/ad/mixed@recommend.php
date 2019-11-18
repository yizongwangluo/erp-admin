<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<?php $fields = ['goods_id' => '物品ID']; ?>
<style type="text/css">
        .list_row {
            clear: both;
        }

        .list_row .add_item {
            float: left;
            width: auto;
        }

        .list_row .tips {
            margin-left: 5px;
            color: red;
            margin-top: 5px;
        }
</style>
<div class="layui-tab admin-layui-tab layui-tab-brief">
<ul class="layui-tab-title">
            <li class=""><a href="<?php echo site_url ( 'admin/ad/lists/' . $tag['action'] ); ?>">广告列表</a></li>
            <li class="layui-this">编辑广告</li>
</ul>
<div class="layui-tab-item layui-show">
    <form method="post" action="<?php echo site_url ( 'admin/ad/save' ); ?>" class="layui-form layui-table">
        <input type="hidden" name="id" value="<?php echo $tag['id'] ?>"/>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">开启手动:</label>
            <div class="layui-inline col-xs-3">
                <input <?php if ( $tag['content']['isopen'] == 1 ) echo 'checked'; ?> class="checkbox" type="radio" value="1"  name="content[isopen]" title="是">
                <input class="checkbox" type="radio" value="0" name="content[isopen]" title="否" <?php if ( $tag['content']['isopen'] == 0 ) echo 'checked'; ?> >
            </div>
        </div>
            <div class="list_row list_row_tpl" style="display: none;">
				<?php foreach ( $fields as $name => $item ): ?>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo is_array ( $item ) ? $item['label'] : $item ?>：</label>
						<div class="layui-inline">
							<?php if ( is_array ( $item ) && isset( $item['tag_type'] ) ): ?>
								<?php if ( $item['tag_type'] == 'select' ): ?>
                                    <select disabled class="txt txtD field_<?php echo $name; ?>"
                                            name="content[list][<?php echo $name; ?>][]">
										<?php foreach ( $item['options'] as $o ): ?>
                                            <option value="<?php echo $o['value']; ?>"><?php echo $o['text']; ?></option>
										<?php endforeach; ?>
                                    </select>
								<?php endif; ?>
							<?php else: ?>
                                <input disabled type="text" class="layui-input field_<?php echo $name; ?>"
                                       name="content[list][<?php echo $name; ?>][]" value="">
							<?php endif; ?>
							<?php if ( is_array ( $item ) && isset( $item['tips'] ) ) {
								echo "<p class=\"tips\">{$item['tips']}</p>";
							} ?>
                        </div>
                        <div class="layui-inline">
                            <button class="row_add_btn layui-btn" type="button" onclick="add_row()">+</button>
                            <button class="row_del_btn layui-btn layui-btn-danger" type="button" onclick="$(this).parent().parent().remove();">-
                            </button>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
<script type="text/javascript">
            //新增一行数据
            function add_row(row_data) {
                if (window.list_row_count == undefined) {
                    window.list_row_count = 1;
                } else {
                    window.list_row_count++;
                }
                var row_tpl_class_name = 'list_row_tpl';
                var row_tpl = $('.' + row_tpl_class_name + ':first');
                var clone_row = $(row_tpl).clone();
                clone_row.removeClass(row_tpl_class_name).show();
                clone_row.find('input,select').attr('disabled', false);
                if (window.list_row_count == 1) {
                    clone_row.find('.row_del_btn').hide();
                } else {
                    clone_row.find('.row_add_btn').hide();
                }
                if (row_data != undefined) {
                    for (var k in row_data) {
                        clone_row.find('.field_' + k + ':first').val(row_data[k]);
                    }
                }
                $('.list_row:last').after(clone_row);
            }
            //页面初始化时,加载数据
			<?php foreach($tag['content']['list'] as $item): ?>
            add_row(<?php echo json_encode ( $item ); ?>);
			<?php endforeach; ?>
			<?php if(!$tag['content']['list']): ?>
            add_row();
			<?php endif;?>
</script>
<div class="layui-form-item">
 <div class="layui-input-block">
  <button class="layui-btn" data-url="<?php echo site_url ( 'admin/ad/lists/' . $tag['action'] ); ?>" type="button" lay-submit lay-filter="post">保存</button>
  </div>
</div>
    </form>
</div>
</div>
<?php $this->load->view ( 'admin/common/footer' ) ?>