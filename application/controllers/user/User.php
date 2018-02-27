<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class User extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user/User_model', 'user');
    }

    public function register() {
        $params = $this->input->post();

        $key = array(
            'phone',
            'password'
        );
        $option = elements($key, $params, '');
        // 数据校验
        if (!is_phone($option['phone'])) {
            $this->return_result(fail('手机号格式错误'));
        }
        if (strlen($option['password']) < 6) {
            $this->return_result(fail('密码不能少于6位'));
        }

        $result = $this->user->register($option['phone'], $option['password']);
        
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
            $this->return_result(fail('手机号格式错误'));
        }
        if (strlen($option['password']) < 6) {
            $this->return_result(fail('密码不能少于6位'));
        }

        $result = $this->user->login($option['phone'], $option['password']);

        $this->return_result($result);
    }

    public function add() {
        for($i = 0;$i < 200;$i++) {
            $this->user->register(13000000004 + $i, '000000');
        }
        $this->return_success('添加完成');
    }
}
