<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>
    <style type="text/css">
        .list_row{
            clear: both;
        }
        .list_row .add_item{
            width: 100%;
        }

        .list_row .tips{
            margin-left: 5px;
            color: red;
            margin-top: 5px;
        }
    </style>
    <div class="main clear">
        <div class="tool">
            <a href="<?php echo site_url('admin/ad/lists');?>" class="button">返回广告列表</a>

            <div style="margin-left: 30%;">
                <input id="thumb_img" name="thumb_img" value="" type="text" class="txt txtB" />
                <a id="thumb_img_btn" href="javascript:void(0)" class="button" >上传图片</a>
                <script type="text/javascript">new custom_uploader('thumb_img_btn','thumb_img');</script>
            </div>
        </div>

        <form method="post" action="<?php echo site_url('admin/ad/save'); ?>">
            <input type="hidden" name="id" value="<?php echo $tag['id'] ?>" />
            <div class="add_l" style="width: 100%; border-right: none;">
                <div class="list_row list_row_tpl" >
                    <?php foreach($fields as $name=>$item): ?>
                        <div class="add_item">
                            <label><?php echo is_array($item)?$item['label']:$item?>：</label>

                            <?php if( is_array($item) && isset($item['tag_type']) ): ?>
                                <?php if($item['tag_type']=='select'): ?>
                                    <select disabled class="txt txtD field_<?php echo $name; ?>" name="content[<?php echo $name; ?>]">
                                        <?php foreach($item['options'] as $o): ?>
                                            <option value="<?php echo $o['value']; ?>"><?php echo $o['text']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                                <?php if($item['tag_type']=='textarea'): ?>
                                    <textarea id="editor_<?php echo $name; ?>" rows="20" cols="150" name="content[<?php echo $name; ?>]"><?php echo $tag['content'][$name]; ?></textarea>
                                    <script type="text/javascript">new custom_editor('editor_<?php echo $name; ?>');</script>
                                <?php endif; ?>
                            <?php else: ?>
                                <input type="text" class="txt txtC field_<?php echo $name; ?>" name="content[<?php echo $name; ?>]" value="<?php echo $tag['content'][$name]; ?>">
                            <?php endif; ?>

                            <?php if(is_array($item) && isset($item['tips'])){
                                echo "<p class=\"tips\">{$item['tips']}</p>";
                            } ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="btn_box">
                <input type="submit" value="保&nbsp;&nbsp;存">
            </div>
        </form>
    </div>
<?php $this->load->view('admin/common/footer')?>