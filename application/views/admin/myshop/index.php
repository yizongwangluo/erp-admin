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
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
                <div style='overflow:auto'>
                    <table class="layui-table"  style='white-space: nowrap'>
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>域名</td>
                        <td>后台地址</td>
                        <td>客服邮箱</td>
                        <td>收款paypal
                            <span class="layui-table-sort layui-inline">
                    <a href='index?title=receipt_paypal&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=receipt_paypal&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>收款信用卡通道
                            <span class="layui-table-sort layui-inline">
                    <a href='index?title=receipt_credit_card&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=receipt_credit_card&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>扣款方式
                            <span class="layui-table-sort layui-inline">
                    <a href='index?title=deduction&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=deduction&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>店铺套餐</td>
                        <td>授权ERP
                            <span class="layui-table-sort layui-inline">
                    <a href='index?title=authorization_erp&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=authorization_erp&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>所属企业主体
                            <span class="layui-table-sort layui-inline">
                    <a href='index?title=company_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=company_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>所属人
                            <span class="layui-table-sort layui-inline">
                    <a href='index?title=real_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=real_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){ ?>
                        <?php foreach ($data as $v): ?>
                            <tr>
                                <td><?=$v['id']?></td>
                                <td><?=$v['domain']?></td>
                                <td><?=$v['backstage']?></td>
                                <td><?=$v['customer_service_email']?></td>
                                <td>
                                    <?php
                                    if(strpos($v['receipt_paypal'],',') !== false) {
                                        $receipt_paypals = explode(',',$v['receipt_paypal']);
                                        foreach ($receipt_paypals as $receipt_paypal){
                                            echo $receipt_paypal."<br>";
                                        }
                                    }else{
                                        echo $v['receipt_paypal'];
                                    }
                                    ?>
                                </td>
                                <td><?=$v['receipt_credit_card']?></td>
                                <td><?=$v['deduction']?></td>
                                <td>
                                    <?php
                                    if(strpos($v['shop_package'],',') !== false) {
                                        $shop_packages = explode(',',$v['shop_package']);
                                        foreach ($shop_packages as $shop_package){
                                            echo $shop_package."<br>";
                                        }
                                    }else{
                                        echo $v['shop_package'];
                                    }
                                    ?>
                                </td>
                                <td><?=$v['authorization_erp']?></td>
                                <td><?=$v['company_name']?></td>
                                <td><?=$v['real_name']?></td>
                                <td><button class="layui-btn-xs layui-btn layui-btn-normal" type="button"  data-modal="<?php echo base_url ( 'admin/myshop/detail/'.$v['id'] ) ?>"  data-title="店铺详情" data-width="450px">详情</button></td>
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
