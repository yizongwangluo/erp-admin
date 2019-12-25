<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">店铺列表</li>
        <li class=""><a href='<?php echo base_url ( 'admin/shop/add' ) ?>'>新增店铺</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/lists' ) ?>'>申请列表</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/unreviewed' ) ?>'>待审批</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/rejected' ) ?>'>已驳回</a></li>
        <li><a href='<?php echo base_url ( 'admin/shop/reviewed' ) ?>'>已完成</a></li>
    </ul>
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
                        <td>网站域名</td>
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
                    <a href='index?title=user_name&sort=asc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-asc"></i></a>
                    <a href='index?title=user_name&sort=desc&search=<?php echo $this->input->get ( 'search' ); ?>'><i class="layui-edge layui-table-sort-desc"></i></a>
                </span>
                        </td>
                        <td>备注</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){ ?>
                        <?php foreach ($data as $v): ?>
                            <tr>
                                <td><?=$v['id']?></td>
                                <td><?=$v['domain']?></td>
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
                                <td><?=$v['user_name']?></td>
                                <td><?=$v['shop_remark']?></td>
                                <td>
                                    <a href='<?php echo base_url ( 'admin/shop/edit/'.$v['id'] ) ?>'><button type="button" class="layui-btn layui-btn-sm"><i class="layui-icon"></i></button></a>
                                    <button data-url="<?php echo base_url ( 'admin/shop/del' ) ?>" data-id="<?= $v['id'] ?>" type="button" class="layui-btn layui-btn-danger layui-btn-sm confirm_post"><i class="layui-icon"></i></button>
                                </td>
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
