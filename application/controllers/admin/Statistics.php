<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Statistics extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        if(!$this->super) {
            $this->return_fail('您没有权限');
        }
        $this->load->model('admin/super/Statistics_model', 'statistics');
    }

    public function get_statistics() {
        return $this->return_result($this->statistics->get_statistics());
    }
}
