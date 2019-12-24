<?php
/**
 * Created by PhpStorm.
 * User: zhoufang
 * Date: 2019/12/3
 * Time: 12:02
 */

class Companyaccount_data extends \Application\Component\Common\IData
{
    public function __construct ()
    {
        parent::__construct ();
    }

    public function  add ( array $in )
    {
        if (empty($in['company_account_id'])) {
            $this->set_error(' 请输入企业账户ID！');
            return false;
        }
        if (empty($in['shop_id'])) {
            $this->set_error(' 请选择网站域名！');
            return false;
        }
        if (empty($in['isunlock'])) {
            $this->set_error(' 请选择是否解限！');
            return false;
        }
        if (!is_numeric($in['status'])) {
            $this->set_error(' 请选择账户状态！');
            return false;
        }

        $id = $in['id'];
        $shop_id = $in['shop_id'];
        $belongs = $this->db->query ( "select company_id,user_id from shop where id = $shop_id" )->row_array ();
        $company_id = $belongs['company_id'];
        $user_id = $belongs['user_id'];

        $data = array(
            'company_account_id' => $in['company_account_id'],
            'shop_id' => $in['shop_id'],
            'company_id' => $company_id,
            'isunlock' => $in['isunlock'],
            'status' => $in['status'],
            'user_id' => $user_id,
            'companyaccount_remark' => $in['companyaccount_remark']
        );

        function  filtrfunction($arr){
            if($arr === '' || $arr === null){
                return false;
            }
            return true;
        }

        if (!$id) {
            $data = array_filter($data,'filtrfunction');
            if (!$this->store($data)) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
        }else{
            unset($in['id']);
            if (!$this->companyaccount_data->update($id,$data)){
                $this->set_error ('数据更新失败，请稍后再试！');
                return false;
            }
        }
        return true;
    }

    public function get_domain($company_id)
    {
        $sql = "select id,domain from shop where company_id = $company_id order by id desc";
        $domains = $this->db->query ( $sql )->result_array ();
        return $domains;
    }
}