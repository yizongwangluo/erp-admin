<?php

/**
 * 表单相关业务
 * User: blueidea
 * Date: 2016/12/23
 * Time: 9:54
 */

class Form_facade extends \Application\Component\Common\IFacade {

    function __construct() {
        parent::__construct();
        $this->load->model('data/form_data');
        $this->load->model('data/form_data_data');
        $this->load->model('data/form_type_data');
    }

    // +----------------------------------------------------------------------
    // | Describe: 表单管理
    // +----------------------------------------------------------------------

    /**
     * 发布
     * @param array $response
     * @return bool
     */

    public function create(array $response) {

        if(!$response['fname']){
            $this->set_error('名称不能为空');
            return false;
        }

        if(!$response['fmsg']){
            $this->set_error('说明不能为空');
            return false;
        }

        if(!$this->form_data->store([
            'fname' => $response['fname'],
            'fmsg' => $response['fmsg'],
            'display' => $response['display'],
            'pubdate' => time()
            ])) {
                $this->set_error('新增失败');
                return false;
            }

        return true;

    }

    /**
     * 更新
     * @param $response
     * @return bool
     */

    public function update($response) {

        if(!$response['fname']){
            $this->set_error('名称不能为空');
            return false;
        }

        if(!$response['fmsg']){
            $this->set_error('说明不能为空');
            return false;
        }

        if(!$this->form_data->update($response['id'], [
            'fname' => $response['fname'],
            'fmsg' => $response['fmsg'],
            'display' => $response['display']
        ])){
            $this->set_error('修改失败');
            return false;
        }

        return true;

    }

    /**
     * 表单删除
     * @param $id
     * @return bool
     */

    public function delete($id) {
        if(!$this->form_data->delete($id)){
            $this->set_error('删除失败');
            return false;
        }
        return true;
    }

    /**
     * 修改状态
     * @param $id
     * @return bool
     */

    public function display($id) {
        $data = $this->form_data->get_info($id);

        $display = $data['display'] ? 0 : 1;

        if (!$this->form_data->update($id, ['display' => $display])) {
            $this->set_error('修改失败');
            return false;
        }

        return true;
    }

    // +----------------------------------------------------------------------
    // | Describe: 选项管理
    // +----------------------------------------------------------------------

    /**
     * 发布
     * @param array $response
     * @return bool
     */

    public function createoption(array $response) {

        if(!$response['title']){
            $this->set_error('名称不能为空');
            return false;
        }

        if(!$response['msg']){
            $this->set_error('说明不能为空');
            return false;
        }

        if(!$response['type']){
            $this->set_error('类型不能为空');
            return false;
        }

        if(!$this->form_type_data->store([
            'fid' => $response['fid'],
            'title' => $response['title'],
            'msg' => $response['msg'],
            'type' => $response['type'],
            'options' => $response['options'],
            'defaultvalue' => $response['defaultvalue'],
            'ismust' => $response['ismust'],
            'orderid' => $response['orderid']
        ])) {
            $this->set_error('新增失败');
            return false;
        }

        return true;

    }

    /**
     * 更新
     * @param $response
     * @return bool
     */

    public function updateoption($response) {

        if(!$response['title']){
            $this->set_error('名称不能为空');
            return false;
        }

        if(!$response['msg']){
            $this->set_error('说明不能为空');
            return false;
        }

        if(!$response['type']){
            $this->set_error('类型不能为空');
            return false;
        }

        if(!$this->form_type_data->update($response['id'], [
            'fid' => $response['fid'],
            'title' => $response['title'],
            'msg' => $response['msg'],
            'type' => $response['type'],
            'options' => $response['options'],
            'defaultvalue' => $response['defaultvalue'],
            'ismust' => $response['ismust'],
            'orderid' => $response['orderid']
        ])){
            $this->set_error('修改失败');
            return false;
        }

        return true;

    }

    /**
     * 表单选项
     * @param $id
     * @return bool
     */

    public function deleteoption($id) {
        if(!$this->form_type_data->delete($id)){
            $this->set_error('删除失败');
            return false;
        }
        return true;
    }

    // +----------------------------------------------------------------------
    // | Describe: 提交管理
    // +----------------------------------------------------------------------

    public function createcontent($response) {
//        $condition = " fid = '{$response['id']}' ";
//        $data = $this->form_type_data->lists($condition, ['orderid', 'asc']);

        echo 'a';exit;

        foreach($data AS $k => $t) {
            checkMust($data[$k]['ismust'], $response['content'][$k]);
            $title[] = $t['title'];
        }

        $array = array('content' => $response['content'], 'title' => $title);

        $intoArr = serialize($array);
        $time = time();

        if(!$this->form_data_data->store([
            'fid' => $response['fid'],
            'content' => $intoArr,
            'addtime' => $time
        ])) {
            $this->set_error('新增失败');
            return false;
        }

        return true;

    }

}