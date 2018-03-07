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
        $link = get_param($params, 'link', '');
        $title = get_param($params, 'title', '');
        $position = get_param($params, 'position', 1);
        if($banner == '') {
            $this->return_fail('banner 参数错误');
        }
        $this->return_success($this->common->add_banner($banner, $link, $position, $title));
    }

    public function edit_banner() {
        $params = $this->input->post();
        $id = get_param($params, 'id', '');
        $banner = get_param($params, 'banner', '');
        $link = get_param($params, 'link', '');
        $title = get_param($params, 'title', '');
        $position = get_param($params, 'position', 1);
        if($id == '') {
            $this->return_fail('id 参数错误');
        }
        if($banner == '') {
            $this->return_fail('banner 参数错误');
        }
        $this->return_result($this->common->edit_banner($id, $banner, $link, $position, $title));
    }

    public function under_banner() {
        $params = $this->input->post();
        $id = get_param($params, 'id', '');
        if($id == '') {
            $this->return_fail('id 参数错误');
        }
        $this->return_result($this->common->under_banner($id));
    }

    public function get_banner_list() {
        $this->return_success($this->common->get_banner_list());
    }
}
