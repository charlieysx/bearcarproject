<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Statistics_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_statistics() {
        $thisTime = time();

        $allCount = intval($this->db->from(TABLE_CAR)->count_all_results());
        $sellCount = intval($this->db->from(TABLE_ORDER)->where('step', '4')->count_all_results());
        $successCount = intval($this->db->from(TABLE_ORDER)->where('status', '3')->count_all_results());
        $userCount = intval($this->db->from(TABLE_USER)->count_all_results());

        $cityData = $this->db->from(TABLE_CAR)
                        ->select('city.name as cityName, lng, lat, count(*) as count')
                        ->join(TABLE_CITY, 'city.id = car.licensed_city_id')
                        ->group_by('car.licensed_city_id')
                        ->order_by('count(*)', 'DESC')
                        ->get()
                        ->result_array();

        return success(array(
            'allCount'=> $allCount,
            'sellCount'=> $sellCount,
            'successCount'=> $successCount,
            'userCount'=> $userCount,
            'cityData'=> $cityData
        ));
    }
}