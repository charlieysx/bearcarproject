<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class City extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('common/City_model', 'city');
    }

    public function get_province() {
        $params = $this->input->get();
        $province_id = get_param($params, 'provinceId');
        $result = $this->city->get_province($province_id);
        $this->return_result($result);
    }

    public function get_city() {
        $params = $this->input->get();
        $city_id = get_param($params, 'cityId');
        $result = $this->city->get_city($city_id);
        $this->return_result($result);
    }

    public function get_city_by_province_id() {
        $params = $this->input->get();
        $province_id = get_param($params, 'provinceId');
        if($province_id == '') {
            $this->return_fail('无效的 provinceId : ');
        }
        $result = $this->city->get_city_by_province_id($province_id);
        $this->return_result($result);
    }

    public function get_district_by_city_id() {
        $params = $this->input->get();
        $city_id = get_param($params, 'cityId');
        if($city_id == '') {
            $this->return_fail('无效的 cityId : ');
        }
        $result = $this->city->get_district_by_city_id($city_id);
        $this->return_result($result);
    }

    public function get_info_by_city() {
        $params = $this->input->get();
        $city_id = get_param($params, 'cityId');
        if($city_id == '') {
            $this->return_fail('无效的 cityId : ');
        }
        $result = $this->city->get_info_by_city($city_id);
        $this->return_result($result);
    }

    public function get_city_sort() {
        $result = $this->city->get_city_sort();
        $this->return_success($result);
    }

    public function get_hot_city() {
        $params = $this->input->get();
        $count = get_param($params, 'count', '10');
        $result = $this->city->get_hot_city($count);
        $this->return_success($result);
    }
}
