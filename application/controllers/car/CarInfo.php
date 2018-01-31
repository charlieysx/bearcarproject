<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class CarInfo extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('car/CarInfo_model', 'car_info');
    }

    public function get_brand() {
        $param = $this->input->get();
        $brand_id = '';
        if(isset($param['brandId'])) {
            $brand_id = $param['brandId'];
        }
        $result = $this->car_info->get_brand($brand_id);
        $this->return_result($result);
    }

    public function get_series_by_brand_id() {
        $param = $this->input->get();
        $brand_id = '';
        if(isset($param['brandId'])) {
            $brand_id = $param['brandId'];
        }
        $result = $this->car_info->get_series_by_brand_id($brand_id);
        $this->return_result($result);
    }

    public function get_model_by_series_id() {
        $param = $this->input->get();
        $series_id = '';
        if(isset($param['seriesId'])) {
            $series_id = $param['seriesId'];
        }
        $result = $this->car_info->get_model_by_series_id($series_id);
        $this->return_result($result);
    }
}
