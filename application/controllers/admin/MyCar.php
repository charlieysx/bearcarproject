<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class MyCar extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        if($this->super) {
          $this->load->model('admin/super/MyCar_model', 'my_car');
        } else {
          $this->load->model('admin/MyCar_model', 'my_car');
        }
    }

    public function get_car_list() {
        if($this->super) {
            $this->super_get_car_list();
        } else {
            $params = $this->input->get();
            $pageOpt = get_page($params);

            $type = get_param($params, 'type');
            $car_status = 0;
            switch($type) {
              case 'waiting':
                $car_status = 0;
                break;
              case 'selling':
                $car_status = 1;
                break;
              case 'ordering':
                $car_status = 2;
                break;
              case 'under':
                $car_status = 3;
                break;
              case 'checking':
                $car_status = 6;
                break;
              default:
                $type = 'waiting';
                $car_status = 0;
                break;
            }
            $carList = $this->my_car->get_car_list($this->token->userInfo['user_id'], $car_status, $pageOpt['page'], $pageOpt['pageSize']);
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
    }

    public function super_get_car_list() {
      $params = $this->input->get();
      $pageOpt = get_page($params);

      $type = get_param($params, 'type');
      $car_status = 0;
      switch($type) {
        case 'waiting':
          $car_status = 0;
          break;
        case 'selling':
          $car_status = 1;
          break;
        case 'ordering':
          $car_status = 2;
          break;
        case 'under':
          $car_status = 3;
          break;
        case 'checking':
          $car_status = 6;
          break;
        default:
          $type = 'waiting';
          $car_status = 0;
          break;
      }
      $carList = $this->my_car->get_car_list($car_status, $pageOpt['page'], $pageOpt['pageSize']);
      $carCount = $this->my_car->get_mycar_count($car_status);

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
        if($this->super) {
            $this->super_under();
        } else {
            $params = $this->input->post();
            $carId = get_param($params, 'carId', '');
            $underReason = get_param($params, 'underReason', '');
            if($carId == '') {
                $this->return_fail('carId错误');
            }
            if($underReason == '') {
                $this->return_fail('请填写下架原因');
            }
            $result = $this->my_car->under($this->token->userInfo['user_id'], $carId, $underReason);
            $this->return_result($result);
        }
    }

    public function super_under() {
        $params = $this->input->post();
        $carId = get_param($params, 'carId', '');
        $underReason = get_param($params, 'underReason', '');
        if($carId == '') {
            $this->return_fail('carId错误');
        }
        if($underReason == '') {
            $this->return_fail('请填写下架原因');
        }
        $result = $this->my_car->under($this->token->userInfo['user_id'], $carId, $underReason);
        $this->return_result($result);
    }

    public function order_check() {
        $params = $this->input->post();
        $carId = get_param($params, 'carId', '');
        if($carId == '') {
          $this->return_fail('carId错误');
        }
        $result = $this->my_car->order_check($this->token->userInfo['user_id'], $carId);
        $this->return_result($result);
    }

    public function get_mycar_info() {
        $params = $this->input->get();
        $carId = get_param($params, 'carId', '');
        if($carId == '') {
          $this->return_fail('carId错误');
        }
        $result = $this->my_car->get_mycar_info($this->token->userInfo['user_id'], $carId);
        $this->return_result($result);
    }
}
