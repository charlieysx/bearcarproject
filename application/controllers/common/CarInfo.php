<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class CarInfo extends Base_Controller
{
    public function __construct() {
        parent::__construct();

        $this->load->model('common/CarInfo_model', 'car_info');
    }

    public function get_brand() {
        $params = $this->input->get();
        $brand_id = get_param($params, 'brandId');
        $result = $this->car_info->get_brand($brand_id);
        $this->return_result($result);
    }

    public function get_series_by_brand_id() {
        $params = $this->input->get();
        $brand_id = get_param($params, 'brandId');
        if($brand_id == '') {
            $this->return_fail('无效的 brandId : ');
        }
        $result = $this->car_info->get_series_by_brand_id($brand_id);
        $this->return_result($result);
    }

    public function get_model_by_series_id() {
        $params = $this->input->get();
        $series_id = get_param($params, 'seriesId');
        if($series_id == '') {
            $this->return_fail('无效的 seriesId : ');
        }
        $result = $this->car_info->get_model_by_series_id($series_id);
        $this->return_result($result);
    }

    public function get_brand_sort() {
        $result = $this->car_info->get_brand_sort();
        $this->return_success($result);
    }

    public function get_hot_brand() {
        $params = $this->input->get();
        $count = get_param($params, 'count', '10');
        $result = $this->car_info->get_hot_brand($count);
        $this->return_success($result);
    }

    public function get_hot_series() {
        $params = $this->input->get();
        $count = get_param($params, 'count', '10');
        $result = $this->car_info->get_hot_series($count);
        $this->return_success($result);
    }
}
