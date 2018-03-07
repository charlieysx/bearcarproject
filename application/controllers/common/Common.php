<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Common extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('common/Common_model', 'common');
    }

    public function get_banner() {
        $this->return_success($this->common->get_banner());
    }

    public function add_banner() {
        $params = $this->get();
        $this->return_success($this->common->add_banner());
    }
}
