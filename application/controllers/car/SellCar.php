<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class SellCar extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('car/SellCar_model', 'sell_car');
    }

    public function sell_car() {
        $this->check_token();
        $param = $this->input->post();
        $result = $this->sell_car->sell_Car($this->token->userInfo['user_id'], $param);
        $this->return_result($result);
    }
}
