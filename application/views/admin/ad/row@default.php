<?php
$this->load->view(
    $common_view,
    array(
        'fields'=>array(
            'title'=>'标题',
            'url'=>'链接',
            'img'=>'图片',
        ),
    )
);