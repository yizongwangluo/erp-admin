<?php $this->load->view('common/top-bar'); ?>
<?php $this->load->view('common/head-bar'); ?>
<?php $this->load->view('common/nav-bar', ['nav_active' => empty($nav_active) ? 'index': $nav_active]); ?>