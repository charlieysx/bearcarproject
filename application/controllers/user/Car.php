<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Car extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user/Car_model', 'car');
    }

    public function order_car() {
      $this->check_token();
      $params = $this->input->post();
      $carId = get_param($params, 'carId');
      $userName = get_param($params, 'userName');
      if ($carId == '') {
        $this->return_fail('carId错误');
      }
      if ($userName == '') {
        $this->return_fail('userName不能为空');
      }
      $result = $this->car->order_car($this->token->userInfo['user_id'], $userName, $carId);
      $this->return_result($result);
    }
}
