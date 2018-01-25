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

    public function get_city_sort() {
        $result = $this->city->get_city_sort();
        $this->return_result($result);
    }
}
