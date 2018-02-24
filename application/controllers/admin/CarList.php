<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class CarList extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/CarList_model', 'car_list');
    }

    public function get_car_list() {
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
        $result = $this->car_list->get_car_list($opt['type'], $opt['page'], $opt['pageSize']);
        $this->return_result($result);
    }

    public function under() {
      $this->check_token();
      $param = $this->input->post();
      $k = array(
        'carId'
      );
      $opt = elements($k, $param, '');
      $result = $this->car_list->under($opt['carId']);
      $this->return_result($result);
    }
}
