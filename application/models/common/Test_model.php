<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Test_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function test() {
        // $car = $this->db->from(TABLE_CAR)
        //                     ->select('licensed_month, car_id')
        //                     ->where('month(str_to_date(licensed_month, \'%m月\')) = ', '2')
        //                     ->get()
        //                     ->result_array();


        // $year = intval(date('Y', strtotime('-1year')));
        // $month = intval(date('m'));

        // $data = array($year, $month);

        // $car = $this->db->from(TABLE_CAR)
        //                 ->select('licensed_month, licensed_year, car_id')
        //                 ->group_start()
        //                     ->where('year(str_to_date(licensed_year, \'%Y年\')) > ', 2017)
        //                     ->or_group_start()
        //                         ->where('year(str_to_date(licensed_year, \'%Y年\')) = ', 2017)
        //                         ->where('month(str_to_date(licensed_month, \'%m月\')) >= ', 3)
        //                     ->group_end()
        //                 ->group_end()
        //                 ->get()
        //                 ->result_array();

        $car = $this->db->from(TABLE_CONFIG_BASE)
                        ->select('speed, car_id')
                        ->like('config_base.speed', '', 'both')
                        ->get()
                        ->result_array();
        
        return success($car);
    }
}