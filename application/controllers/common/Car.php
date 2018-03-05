<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Car extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('common/Car_model', 'car');
    }

    public function get_car_info() {
        $params = $this->input->get();
        $carId = get_param($params, 'carId', '');
        if($carId == '') {
          $this->return_fail('carId错误');
        }
        $result = $this->car->get_car_info($carId);
        $this->return_result($result);
    }

    public function get_car_list() {
        $params = $this->input->post();
        $pageOpt = get_page($params);
        $result = $this->car->get_car_list($params, $pageOpt['page'], $pageOpt['pageSize']);
        $this->return_result($result);
    }
}
