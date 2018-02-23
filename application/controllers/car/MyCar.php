<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class MyCar extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('car/MyCar_model', 'my_car');
    }

    public function get_my_car() {
        $this->check_token();
        $param = $this->input->get();
        $k = array(
          'page',
          'pageSize',
          'type'
        );
        $opt = elements($k, $param, '');
        $opt['page'] = intval($opt['page']);
        $opt['pageSize'] = intval($opt['pageSize']);
        if($opt['page'] < 0) {
          $opt['page'] = 0;
        }
        if($opt['pageSize'] > 99 || $opt['pageSize'] <= 0) {
          $opt['pageSize'] = 15;
        }
        $result = $this->my_car->get_my_car($this->token->userInfo['user_id'], $opt['type'], $opt['page'], $opt['pageSize']);
        $this->return_result($result);
    }

    public function under() {
      $this->check_token();
      $param = $this->input->post();
      $k = array(
        'carId'
      );
      $opt = elements($k, $param, '');
      $result = $this->my_car->under($this->token->userInfo['user_id'], $opt['carId']);
      $this->return_result($result);
    }
}
