<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class MyCar extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('user/MyCar_model', 'my_car');
    }

    public function get_my_car() {
        $this->check_token();
        $params = $this->input->get();
        $pageOpt = get_page($params);

        $type = get_param($params, 'type');
        $car_status = 0;
        switch($type) {
          case 'waiting':
            $car_status = 0;
            break;
          case 'checking':
            $car_status = 6;
            break;
          case 'selling':
            $car_status = 1;
            break;
          case 'under':
            $car_status = 2;
            break;
          default:
            $type = 'waiting';
            $car_status = 0;
            break;
        }
        $carList = $this->my_car->get_my_car($this->token->userInfo['user_id'], $car_status, $pageOpt['page'], $pageOpt['pageSize']);
        $carCount = $this->my_car->get_mycar_count($this->token->userInfo['user_id'], $car_status);

        $result = array(
          'type'=> $type,
          'page'=> $pageOpt['page'],
          'pageSize'=> $pageOpt['pageSize'],
          'count'=> $carCount['msg'],
          'list'=> $carList['msg']
        );

        $this->return_success($result);
    }

    public function under() {
      $this->check_token();
      $params = $this->input->post();
      $carId = get_param($params, 'carId');
      $result = $this->my_car->under($this->token->userInfo['user_id'], $carId);
      $this->return_result($result);
    }
}
