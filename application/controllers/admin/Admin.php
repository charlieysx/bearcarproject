<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Admin extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/Admin_model', 'admin');
    }

    public function register() {
        $params = $this->input->post();

        $key = array(
            'phone',
            'userName',
            'password'
        );
        $option = elements($key, $params, '');
        // 数据校验
        if (!is_phone($option['phone'])) {
            $this->return_fail('手机号格式错误');
        }
        if ('' == $option['userName']) {
            $this->return_fail('用户名不能为空');
        }
        if (strlen($option['password']) < 6) {
            $this->return_fail('密码不能少于6位');
        }

        $result = $this->admin->register($option['phone'], $option['userName'], $option['password']);

        $this->return_result($result);
    }

    public function login() {
        $params = $this->input->post();

        $key = array(
            'phone',
            'password'
        );
        $option = elements($key, $params, '');
        // 数据校验
        if (!is_phone($option['phone'])) {
            $this->return_fail('手机号格式错误');
        }
        if (strlen($option['password']) < 6) {
            $this->return_fail('密码不能少于6位');
        }
        $result = $this->admin->login($option['phone'], $option['password']);
        $this->return_result($result);
    }
}
