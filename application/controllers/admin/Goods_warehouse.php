<?php
/**
 * Created by PhpStorm.
 * User: ghj
 * Date: 2020/1/6
 * Time: 14:03
 */

/**
 * 仓库管理
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Goods_warehouse extends \Application\Component\Common\AdminPermissionValidateController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('data/goods_warehouse_data');
    }

    /**
     * 列表
     */
    public function index(){

        $where = $this->input->get();

        $where = array_filter($where);

        $lists = $this->goods_warehouse_data->lists_page($where);

        $this->load->view('',$lists);

    }

    /**
     * 修改
     * @param int $id
     */
    public function edit($id = 0){

        //查询仓库信息
        $info = $this->goods_warehouse_data->get_info($id);

        $this->load->view('@/add',['info'=>$info]);

    }

    /**
     * 保存
     */
    public function save(){

        $input = $this->input->post();
        $id = $input['id'];
        unset($input['id']);

        if($id){ //修改
            $ret = $this->goods_warehouse_data->edit($id,$input);
        }else{ //新增
            $ret = $this->goods_warehouse_data->add($input);
        }
        if(!$ret){
            $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->goods_warehouse_data->get_error() );
        }else{
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, '操作成功！' );
        }

    }

    /**
     *  删除
     */
    public function del(){
        $id = input('id');
        if($id){
            $ret = $this->goods_warehouse_data->delete($id);

            if(!$ret){
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->goods_warehouse_data->get_error() );
            }else{
                $this->output->ajax_return ( AJAX_RETURN_SUCCESS, '删除成功！' );
            }
        }
    }
}