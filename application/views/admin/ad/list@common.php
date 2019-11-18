<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
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
            <form method="post" class="layui-form layui-table" action="<?php echo site_url ( 'admin/ad/save' ); ?>">
                <input type="hidden" name="id" value="<?php echo $tag['id'] ?>"/>
                <div class="list_row list_row_tpl" style="display: none;">
                    <div class="layui-form-item">
						<?php foreach ( $fields as $name => $item ): ?>
                            <div class="layui-inline">
                                <label class="layui-form-label"><?php echo is_array ( $item ) ? $item['label'] : $item ?>
                                    ：</label>
								<?php if ( is_array ( $item ) && isset( $item['tag_type'] ) ): ?>
									<?php if ( $item['tag_type'] == 'select' ): ?>
                                        <select disabled class="field_<?php echo $name; ?>"
                                                name="content[<?php echo $name; ?>][]">
											<?php foreach ( $item['options'] as $o ): ?>
                                                <option value="<?php echo $o['value']; ?>"><?php echo $o['text']; ?></option>
											<?php endforeach; ?>
                                        </select>
									<?php endif; ?>
								<?php else: ?>
                                    <div class="layui-inline">
                                        <input disabled type="text" class="layui-input field_<?php echo $name; ?>"
                                               name="content[<?php echo $name; ?>][]" value="">
                                    </div>
									<?php if ( $name === 'img' ): ?>
                                        <div class="layui-inline">
                                            <input type="file" name="file" class="layui-upload-file field_upload_file">
                                        </div>
									<?php endif; ?>
								<?php endif; ?>
								<?php if ( is_array ( $item ) && isset( $item['tips'] ) ) {
									echo "<p class=\"tips\">{$item['tips']}</p>";
								} ?>
                            </div>
						<?php endforeach; ?>
                        <div class="layui-inline">
                            <button class="row_add_btn layui-btn" type="button" onclick="add_row()">+</button>
                            <button class="row_del_btn layui-btn layui-btn-danger" type="button"
                                    onclick="$(this).parent().parent().remove();">-
                            </button>
                        </div>
                    </div>

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

                            var guid = new GUID();
                            var gid = guid.newGUID();
                            if (row_data != undefined) {
                                for (var k in row_data) {
                                    clone_row.find('.field_' + k + ':first').val(row_data[k]);
                                    if (k === 'img'){
                                        clone_row.find('.field_img:first').addClass('to_'+gid);
                                        clone_row.find('.field_upload_file:first').addClass('f_'+gid);
                                        upImg('.f_'+gid,'.to_'+gid);
                                    }
                                }
                                $('.list_row:last').after(clone_row);
                            }else {
                                var SDLT = clone_row.find('.field_img:first');
                                if (SDLT.length>0){
                                    clone_row.find('.field_img:first').addClass('to_'+gid);
                                    clone_row.find('.field_upload_file:first').addClass('f_'+gid);
                                    $('.list_row:last').after(clone_row);
                                    upImg('.f_'+gid,'.to_'+gid);
                                }
                            }

                        }
                        //页面初始化时,加载数据
	                    <?php foreach($tag['content'] as $k => $item): ?>
                            add_row(<?php echo json_encode ( $item ); ?>);
	                    <?php endforeach; ?>
	                    <?php if(!$tag['content']): ?>
                            add_row();
	                    <?php endif;?>
                </script>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn"
                                data-url="<?php echo site_url ( 'admin/ad/lists/' . $tag['action'] ); ?>" type="button"
                                lay-submit lay-filter="post">保存
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $this->load->view ( 'admin/common/footer' ) ?>