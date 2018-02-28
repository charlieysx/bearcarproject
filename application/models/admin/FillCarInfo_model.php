<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FillCarInfo_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_fill_step($user_id, $car_id) {
        $order = $this->db->from(TABLE_ORDER)
                          ->where('car_id', $car_id)
                          ->where('appraiser_id', $user_id)
                          ->get()
                          ->row_array();
        if(empty($order)) {
            return fail('没有该辆车或您没有权限查看该辆车');
        }

        return success(array('step'=> $order['step']));
    }

    public function get_year_month() {
        //上牌时间
        $year = intval(date( "Y"));
        $month = intval(date( "n"));
        $license_time = array();
        for($i = 0;$i < 10;$i++) {
            $m = array();
            for($j = 1;$j <= ($i == 0 ? $month : 12);$j++) {
                array_push($m, array('value' => $j.'月' ));
            }
            $item = array(
                'value' => ($year - $i).'年',
                'month' => $m
            );
            array_push($license_time, $item);
        }

        return $license_time;
    }
}