<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class SellCar extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->check_token();
        $this->load->model('user/SellCar_model', 'sell_car');
    }

    public function sell_car() {
        $param = $this->input->post();
        $result = $this->sell_car->sell_Car($this->token->userInfo['user_id'], $param);

        $this->return_result($result);
    }

    public function get_sell_info() {
        $result = $this->sell_car->get_sell_info();
        $this->return_success($result);
    }

    public function get_check_time() {
        $result = $this->sell_car->get_check_time();
        $this->return_success($result);
    }
}
