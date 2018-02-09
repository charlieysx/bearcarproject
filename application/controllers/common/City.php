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
        $param = $this->input->get();
        $province_id = '';
        if(isset($param['provinceId'])) {
            $province_id = $param['provinceId'];
        }
        $result = $this->city->get_province($province_id);
        $this->return_result($result);
    }

    public function get_city() {
        $param = $this->input->get();
        $province_id = '';
        if(isset($param['provinceId'])) {
            $province_id = $param['provinceId'];
        }
        $result = $this->city->get_city($province_id);
        $this->return_result($result);
    }

    public function get_district() {
        $param = $this->input->get();
        $city_id = '';
        if(isset($param['cityId'])) {
            $city_id = $param['cityId'];
        }
        $result = $this->city->get_district_by_city_id($city_id);
        $this->return_result($result);
    }

    public function get_city_sort() {
        $result = $this->city->get_city_sort();
        $this->return_result($result);
    }

    public function get_hot_city() {
        $result = $this->city->get_hot_city();
        $this->return_result($result);
    }
}
