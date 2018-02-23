<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class User extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user/User_model', 'user');
    }

    public function user_add() {
        $param = $this->input->post();
        $result = $this->user->add_user($param);
        $this->return_result($result);
    }

    public function user_login() {
        $param = $this->input->post();
        $result = $this->user->login($param);
        $this->return_result($result);
    }

    public function add() {
        for($i = 0;$i < 200;$i++) {
            $param = array(
                'userName'=> 13000000004 + $i,
                'password'=> '000000'
            );
            $this->user->add($param);
        }
        $this->return_result(success_result('添加完成'));
    }
}
