<?php $this->load->view('admin/common/header')?>
<?php $this->load->view('admin/common/menu')?>

<script type="text/javascript">
    var hrefUrl = $('.admin-page-menu').eq(0).attr('href');
    location.href= hrefUrl;
</script>
<?php $this->load->view('admin/common/footer')?>
