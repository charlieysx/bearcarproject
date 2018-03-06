<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Table extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        if(!$this->super) {
            $this->return_fail('您没有权限查看');
        }
        $this->load->model('admin/super/Table_model', 'table');
    }

    public function get_table_user() {
        $params = $this->input->get();
        $pageOpt = get_page($params);
        $sort = get_param($params, 'sort', '');
        $this->return_success($this->table->get_table_user($pageOpt['page'], $pageOpt['pageSize'], $sort));
    }

    public function get_table_admin() {
        $params = $this->input->get();
        $pageOpt = get_page($params);
        $sort = get_param($params, 'sort', '');
        $this->return_success($this->table->get_table_admin($pageOpt['page'], $pageOpt['pageSize'], $sort));
    }
}
