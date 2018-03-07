<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Common extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        if(!$this->super) {
            $this->return_fail('您没有权限');
        }
        $this->load->model('common/Common_model', 'common');
    }

    public function add_banner() {
        $params = $this->input->post();
        $banner = get_param($params, 'banner', '');
        if($banner == '') {
            $this->return_fail('banner 参数错误');
        }
        $this->return_success($this->common->add_banner($banner));
    }

    public function get_banner_list() {
        $params = $this->input->get();
        $pageOpt = get_page($params);
        $this->return_success($this->common->get_banner_list($pageOpt['page'], $pageOpt['pageSize']));
    }
}
