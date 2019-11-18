<?php

/**
 * 表单管理
 * User: blueidea
 * Date: 2016/12/22
 * Time: 14:26
 */

class Form extends \Application\Component\Common\AdminPermissionValidateController {

    function __construct() {
        parent::__construct();
        $this->load->model('data/form_data');
        $this->load->model('data/form_type_data');
        $this->load->model('facade/form_facade');
    }

    // +----------------------------------------------------------------------
    // | Describe: 表单管理
    // +----------------------------------------------------------------------

    /**
     * 表单管理列表
     * @access public
     * @return void
     */

    public function lists() {
        $page = max(1, $this->input->get('page'));
        $page_size = 15;

        $condition = ' 1 = 1 ';

        $keyword = $this->input->get('keyword');
        $keyword && $condition .= " and fname like '%{$keyword}%' ";

        $result = $this->form_data->lists_page($condition, ['id', 'desc'], $page, $page_size);
        $result['page_html'] = create_page_html('?', $result['total'], $page_size);

        $this->load->view('', $result);
    }

    /**
     * 新增表单
     * @access public
     * @return void
     */

    public function add(){
        $this->load->view();
    }

    /**
     * 编辑表单
     * @param $id
     * @access public
     */

    public function edit($id) {
        $data = $this->form_data->get_info($id);
        $this->load->view('@/add', $data);
    }

    /**
     * 保存表单
     * @access public
     * @return void
     */

    public function save() {
        $op = $this->input->post('id') ? 'update' : 'create';

        if(!$this->form_facade->{$op}($this->input->post())){
            $this->output->ajax_return(AJAX_RETURN_FAIL, $this->form_facade->get_error());
        }

        $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok');
    }

    /**
     * 表单删除
     * @param $id
     */

    public function delete($id) {
        if(!$this->form_facade->delete($id)){
            $this->output->ajax_return(AJAX_RETURN_FAIL, $this->form_facade->get_error());
        }

        $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok');
    }

    /**
     * 修改状态
     * @param $id
     */

    public function display($id) {
        if(!$this->form_facade->display($id)){
            $this->output->ajax_return(AJAX_RETURN_FAIL, $this->form_facade->get_error());
        }

        $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok');
    }

    // +----------------------------------------------------------------------
    // | Describe: 选项管理
    // +----------------------------------------------------------------------

    public function optionlists($fid) {
        $page = max(1, $this->input->get('page'));
        $page_size = 15;

        $condition = " fid = '{$fid}' ";

        $keyword = $this->input->get('keyword');
        $keyword && $condition .= " and title like '%{$keyword}%' ";

        $result = $this->form_type_data->lists_page($condition, ['orderid', 'asc'], $page, $page_size);
        $result['page_html'] = create_page_html('?', $result['total'], $page_size);

        $result['fid'] = $fid;

        $this->load->view('', $result);
    }

    /**
     * 新增选项
     * @access public
     * @return void
     */

    public function addoption($fid){
        $this->load->view('', array('fid' => $fid));
    }

    /**
     * 编辑选项
     * @param $id
     * @access public
     */

    public function editoption($id) {
        $data = $this->form_type_data->get_info($id);
        $type = $data['type'];

        $optionls = [
            'text'       => '单行文本(text),display:none',
            'textarea'   => '多行文本(textarea),display:none',
            'select'     => '下拉框(select)',
            'radio'      => '单选框(radio)',
            'checkbox'   => '多选框(checkbox)',
            'password'       => '密码框(password),display:none',
            'hidden'     => '隐藏域(hidden),display:none'
        ];

        $types = explode(',', $optionls[$type]);

        $data = array_merge($data, ['stext' => $types[0]], ['display' => ($types[1] ? $types[1] : '')]);

        $this->load->view('@/addoption', $data);
    }

    /**
     * 保存选项
     * @access public
     * @return void
     */

    public function saveoption() {
        $op = $this->input->post('id') ? 'updateoption' : 'createoption';

        if(!$this->form_facade->{$op}($this->input->post())){
            $this->output->ajax_return(AJAX_RETURN_FAIL, $this->form_facade->get_error());
        }

        $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok');
    }

    /**
     * 表单选项
     * @param $id
     */

    public function deleteoption($id) {
        if(!$this->form_facade->deleteoption($id)){
            $this->output->ajax_return(AJAX_RETURN_FAIL, $this->form_facade->get_error());
        }

        $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok');
    }

    // +----------------------------------------------------------------------
    // | Describe: 提交管理
    // +----------------------------------------------------------------------

    public function submit($id) {

//        $ok=new ChineseSpell();
//        $str= iconv('utf-8', 'gbk', '取汉字所有拼音');
//        echo $ok->getFullSpell($str);
//        echo '<p />';
//        echo $ok->getChineseSpells($str);
//        exit;

//        print_r(unserialize('a:2:{s:7:"content";a:8:{i:0;s:3:"123";i:1;s:3:"123";i:2;s:3:"123";i:3;s:3:"123";i:4;s:3:"123";i:5;s:3:"123";i:6;s:3:"123";i:7;s:11:"男刀客";}s:5:"title";N;}'));exit;

        $formdata = $this->form_data->is_form_data($id, '1');

        if ($formdata) {
            $condition = " fid = '{$id}' ";

            $data = $this->form_type_data->lists($condition, ['orderid', 'asc']);

            $option = '';

            $option .= '<input type="hidden" name="fid" value="'.$id.'" />';
            foreach($data as $k => $form) {
                $k = (!$k) ? 0 : $k++;
                $option .= showForm(
                    $form['type'],
                    $form['title'],
                    $form['options'],
                    $form['defaultvalue'],
                    $form['msg'],
                    $k,
                    $form['ismust']
                );
            }

            $data['option'] = $option;

            $this->load->view('', $data);
        }
    }

    public function contentsave() {
        $op = 'createcontent';

        if(!$this->form_facade->{$op}($this->input->post())){
            $this->output->ajax_return(AJAX_RETURN_FAIL, $this->form_facade->get_error());
        }

        $this->output->ajax_return(AJAX_RETURN_SUCCESS, 'ok');
    }

}