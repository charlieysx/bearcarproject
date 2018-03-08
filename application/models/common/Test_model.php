<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Test_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function test() {
        $car = $this->db->from(TABLE_CAR)
                        ->select('city.name as cityName, count(*) as count')
                        ->join(TABLE_CITY, 'city.id = car.licensed_city_id')
                        ->join(TABLE_CAR_BRAND, 'car_brand.brand_id = car.brand_id')
                        ->group_by('car.licensed_city_id')
                        ->order_by('count(*)', 'DESC')
                        ->get()
                        ->result_array();
        
        return success($car);
    }
}