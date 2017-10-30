<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Admin extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Admin_model', 'admin');
    }

    public function admin_add() {
        $param = $this->input->post();
        $result = $this->admin->add_admin($param);
        if($result['status']) {
            $this->success_response($result);
        } else {
            $this->fail_response($result);
        }
    }

    public function admin_login() {
        $param = $this->input->post();
        $result = $this->admin->login($param);
        if($result['status']) {
            $this->success_response($result);
        } else {
            $this->fail_response($result);
        }
    }
}
