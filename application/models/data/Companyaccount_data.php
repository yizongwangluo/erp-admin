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

    /**
     * 判断是否已存在该数据
     * @param array $input
     * @return bool
     */
    public function removal($input = array()){

        $data = [];

        $data['id !=']      = $input['id'] ? $input['id']:'';
        $data['company_account_id']    	 = $input['company_account_id'];
        $data['shop_id']    	 = $input['shop_id'];

        $data = array_filter($data); //过滤空白数组

        $count = $this->count($data);

        return $count>0;
    }

    public function index ($admin_id)
    {
        if($admin_id == 1){
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.id,
			a.company_account_id,
			a.shop_id,
			a.company_id,
			a.isunlock,
			a.status,
			a.companyaccount_remark,
			a.user_id,
			b.real_name,
			b.user_name,
			c.domain,
			d.company_name
		FROM
			companyaccount a
		LEFT JOIN admin b ON b.id = a.user_id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN company d ON a.company_id = d.id
		GROUP BY
			a.id
	) s";
        }else{
            $sql = "SELECT
	*
FROM
	(
		SELECT
			a.id,
			a.company_account_id,
			a.shop_id,
			a.company_id,
			a.isunlock,
			a.status,
			a.companyaccount_remark,
			a.user_id,
			b.s_real_name AS real_name,
			b.s_user_name AS user_name,
			c.domain,
			d.company_name
		FROM
			companyaccount a
		INNER JOIN (
			SELECT
				s_u_id,
				s_real_name,
				s_user_name
			FROM
				admin_org_temp
			WHERE
				u_id = $admin_id
			GROUP BY
				s_u_id
		) b ON b.s_u_id = a.user_id
		LEFT JOIN shop c ON a.shop_id = c.id
		LEFT JOIN company d ON a.company_id = d.id
	) s";
        }
        return $sql;
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
            if($this->removal($data)){
                $this->set_error(' 该企业账户已存在，无法重复添加！');
                return false;
            }
            $data = array_filter($data,'filtrfunction');
            if (!$this->store($data)) {
                $this->set_error('数据增加失败，请稍后再试~');
                return false;
            }
        }else{
            $data['id'] = $id;
            if($this->removal($data)){
                $this->set_error(' 该企业账户已存在！');
                return false;
            }
            unset($data['id']);
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

    /**
     * 导出
     * @return mixed
     */
    public function daochu(){
        $sql = 'SELECT
                a.*, b.agent,b.belong_to,
                b.domain,
                s.domain AS shop_domain,
                u.user_name
            FROM
                (
                    (
                        companyaccount a
                        LEFT JOIN company b ON a.company_id = b.id
                    )
                    LEFT JOIN shop s ON a.shop_id = s.id
                )
            LEFT JOIN admin u ON b.belong_to = u.id';

        $query = $this->db->query($sql);
        $lists = $query->result_array();
        return $lists;
    }

}