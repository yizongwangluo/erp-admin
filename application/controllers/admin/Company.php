<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/11/19
 * Time: 9:41
 */

class Company extends \Application\Component\Common\AdminPermissionValidateController
{
    public function __construct ()
    {
        parent::__construct ();
        $this->load->model ( 'data/company_data' );
    }

    //企业主体列表
    public function index($title = 'id',$sort = 'desc')
    {
        $admin_id = $this->admin['id'];
        $sql = $this->company_data->index($admin_id);
        $page = max ( 1, $this->input->get ( 'page' ) );
        $condition = $this->parse_query_lists ($this->input->get ());
        $order_t = $this->input->get ( 'title' );
        $order_s = $this->input->get ( 'sort' );
        if(isset($order_t)){
            $title = $order_t;
        }
        if(isset($order_s)){
            $sort = $order_s;
        }
        $result = $this->company_data->list_page ( $sql, $condition, [$title, $sort], $page, 5 );
        $result['page_html'] = create_page_html ( '?', $result['total'],5 );
        $this->load->view('',$result);

    }

    //查询条件
    private function parse_query_lists ($input)
    {
        $condition = array ();
        $search = trim($input['search']);
        if (!empty($search)){
            $condition[] = " where agent like '%{$search}%' or company_name like '%{$search}%' or company_remark like '%{$search}%' or domain like '%{$search}%' or real_name like '%{$search}%'";
        }
        if (empty($condition)){
            return array ();
        }else{
            return $condition;
        }

    }

    //新增企业主体
    public function add(){
        if ( IS_POST ) {
            $input = input ( 'post.' );
            if ( !$this->company_data->add ( $input ) ) {
                $this->output->ajax_return ( AJAX_RETURN_FAIL, $this->company_data->get_error () );
            }
            $this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
        } else {
            $admin_id = $this->admin['id'];
            $data['users'] = $this->company_data->get_users ($admin_id);
            $this->load->view ( '' ,$data);
        }

    }

    //上传营业执照图片
    public function upimage(){
        header("Content-Type:text/html;charset=utf-8");
        $file_path = '/upload/images/';
        $fileName = $_FILES["file"]["name"];
        if ($_FILES["file"]["error"] > 0) {
            echo "错误:" .$_FILES["file"]["error"];
        } else {
            if (file_exists($file_path.$fileName)) {
                if(!unlink(($file_path.$fileName))) {
                    echo "Error deleting $fileName";
                }else{
                    move_uploaded_file($_FILES["file"]["tmp_name"], $file_path.$_FILES["file"]["name"]);
                }
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], FCPATH.$file_path.$_FILES["file"]["name"]);
            }
            $url = $file_path.$fileName;
            $result = ['code'=>0,'msg'=>'','data'=>['src'=>$url]];
            echo json_encode($result);
        }
    }

    //编辑企业主体
    public function edit( $id = null )
    {
        $admin_id = $this->admin['id'];
        $data['info'] = $this->company_data->get_info ( $id );
        $data['users'] = $this->company_data->get_users ( $admin_id );
        $this->load->view ( '@/add', $data );
    }

    //删除企业主体
    public function del()
    {
        $id = (int)$this->input->post ( 'id' );
        if (!$this->company_data->delete($id)) {
            $this->output->ajax_return(AJAX_RETURN_FAIL, '删除失败');
        }
        $this->output->ajax_return(AJAX_RETURN_SUCCESS, '操作成功');
    }

}

