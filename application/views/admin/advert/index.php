<?php $this->load->view ( 'admin/common/header' ) ?>
<?php $this->load->view ( 'admin/common/menu' ) ?>
<div class="layui-tab admin-layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">申请列表</li>
        <li class=""><a href='<?php echo base_url ( 'admin/advert/add' ) ?>'>新增申请</a></li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form action="?" method="get">
                <div class="layui-form">
                    <div class="layui-inline  col-xs-2">
                        <input type="text" name="search" value="<?php echo $this->input->get ( 'search' ); ?>"
                               class="layui-input" placeholder="请输入广告账户ID"/>
                    </div>
                    <div class="layui-inline">
                        <select name="type" lay-verify="required" >
                            <option value="" >--账户类型--</option>
                            <?php foreach($this->enum_field->get_values('advert_type') as $key=>$value){ ?>
                                <option value="<?=$key?>" <?php if ( $this->input->get ( 'type' ) == $key ){echo "selected=\"selected\"";}?>><?=$value?></option>
                            <?php } ?>
                       </select>
                    </div>
                    <div class="layui-inline">
                        <select name="status" lay-verify="required" >
                            <option value="" >--状态--</option>
                            <?php foreach($this->enum_field->get_values('advert_status') as $key=>$value){ ?>
                                <option value="<?=$key?>" <?php if ( $this->input->get ( 'status' ) == $key && is_numeric($this->input->get ( 'status' ))){echo "selected=\"selected\"";}?>><?=$value?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button class="layui-btn layui-btn-danger btn-search" type="submit">搜索
                </div>
                <div style='overflow:auto'>
                    <table class="layui-table"  style='white-space: nowrap'>
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>申请时间</td>
                        <td>账号类型</td>
                        <td>广告ID</td>
                        <td>充值金额</td>
                        <td>剩余金额</td>
                        <td>近两日总花费金额</td>
                        <td>近两日订单量</td>
                        <td>预计日花费</td>
                        <td>申请原因</td>
                        <td>申请人</td>
                        <td>审核员</td>
                        <td>状态</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){ ?>
                        <?php foreach ($data as $v): ?>
                            <tr>
                                <td><?=$v['id']?></td>
                                <td><?=$v['apply_time']?></td>
                                <td><?=$this->enum_field->get_values('advert_type')[$v['type']]?></td>
                                <td><?=$v['advert_id']?></td>
                                <td><?=$v['recharge_amount']?></td>
                                <td><?=$v['remain_amount']?></td>
                                <td><?=$v['total_cost_in_recent_2']?></td>
                                <td><?=$v['today_2_order_sum']?></td>
                                <td><?=$v['estimated_daily_cost']?></td>
                                <td><?=$v['apply_reason']?></td>
                                <td><?=$user_list[$v['u_id']]['user_name']?></td>
                                <td><?=$user_list[$v['audit_u_id']]['user_name']?></td>
                                <td><?=$this->enum_field->get_values('advert_status')[$v['status']]?></td>
                                <td>
                                    <a href='<?php echo base_url ( 'admin/advert/examine/'.$v['id'] ) ?>'  class="layui-btn layui-btn-warm layui-btn-xs <?= $v['status']==0?'':'layui-hide'; ?>">审核</a>
                                    <a href='<?php echo base_url ( 'admin/advert/edit/'.$v['id'] ) ?>' class="<?= $v['status']==1?'layui-hide':''; ?>" ><button type="button" class="layui-btn layui-btn-xs"><i class="layui-icon"></i></button></a>
                                    <button data-url="<?php echo base_url ( 'admin/advert/del' ) ?>" data-id="<?= $v['id'] ?>" type="button" class="layui-btn layui-btn-danger layui-btn-xs confirm_post"><i class="layui-icon"></i></button>
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
