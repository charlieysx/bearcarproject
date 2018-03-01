<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class FillCarInfo extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        $this->load->model('admin/FillCarInfo_model', 'fillcar');
    }

    public function get_fill_step() {
        $params = $this->input->get();
        $carId = get_param($params, 'carId', '');
        if($carId == '') {
          $this->return_fail('carId错误');
        }
        $result = $this->fillcar->get_fill_step($this->token->userInfo['user_id'], $carId);
        $this->return_result($result);
    }

    public function get_year_month() {
        $result = $this->fillcar->get_year_month();
        $this->return_success($result);
    }

    public function get_fill_car_info() {
        $params = $this->input->get();
        $carId = get_param($params, 'carId', '');
        if($carId == '') {
          $this->return_fail('carId错误');
        }
        $result = $this->fillcar->get_fill_car_info($this->token->userInfo['user_id'], $carId);
        $this->return_result($result);
    }

    public function fill_car_first_step() {
        $params = $this->input->post();
        $keyv = array(
          'carId'=> 'carId错误',
          'baseInfo'=> 'baseInfo错误',
          'configBase'=> 'configBase错误',
          'configEngine'=> 'configEngine错误',
          'configChassisBrake'=> 'configChassisBrake错误',
          'configSafety'=> 'configSafety错误',
          'configOut'=> 'configOut错误'
        );
        $key = array(
          'carId',
          'baseInfo',
          'configBase',
          'configEngine',
          'configChassisBrake',
          'configSafety',
          'configOut',
          'configIn'
        );
        $option = elements($key, $params, '');
        foreach($option as $k => $v) {
          if('' == $v) {
            return return_fail($keyv[$k]);
          }
        }
        $result = $this->fillcar->fill_car_first_step($this->token->userInfo['user_id'], $option);
        $this->return_result($result);
    }

    public function fill_car_second_step() {
        $params = $this->input->post();
        $key = array(
          'carId',
          'checkAccident',
          'checkWaterFire',
          'checkCrash',
          'checkBreakablePart',
          'checkSafetySystem',
          'checkOutConfig',
          'checkInConfig',
          'checkLightSystem',
          'checkHighTech',
          'checkTool',
          'checkInstrumentDesk',
          'checkEngineStatus',
          'checkSpeed',
          'checkAppearance'
        );
        $option = elements($key, $params, '');
        foreach($option as $k => $v) {
          if('' == $v) {
            return return_fail($k.'错误');
          }
        }
        $result = $this->fillcar->fill_car_second_step($this->token->userInfo['user_id'], $option);
        $this->return_result($result);
    }
}
