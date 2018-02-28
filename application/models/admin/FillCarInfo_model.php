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

    public function get_fill_car_info($user_id, $car_id) {
        $car = $this->db->from(TABLE_CAR)
                            ->select('car_id as carId, car_brand.brand_name as brandName, car_series.series_name as seriesName, car_model.model_name as modelName, 
                                    licensed_city.name as licensedCityName, licensed_year as licensedYear, licensed_month as licensedMonth, 
                                    car_condition.condition_name as conditionName, expire_date.expire_date_name as expireDateName, mileage, 
                                    transfer_time as transferTime, car.status as status, publish_time as publishTime, see_count as seeCount,
                                    car.inspect_datetime as checkTimeId')
                            ->join(TABLE_CITY.' as licensed_city', 'licensed_city.id = car.licensed_city_id')
                            ->join(TABLE_CAR_BRAND, 'car_brand.brand_id = car.brand_id')
                            ->join(TABLE_CAR_SERIES, 'car_series.series_id = car.series_id')
                            ->join(TABLE_CAR_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(TABLE_CAR_CONDITION, 'car_condition.condition_id = car.car_condition_id')
                            ->join(TABLE_EXPIRE_DATE, 'expire_date.expire_date_id = car.expire_date_id')
                            ->join(TABLE_USER, 'user.user_id = car.user_id')
                            ->where('deal_user_id', $user_id)
                            ->where('car_id', $car_id)
                            ->get()->row_array();
        
        if(empty($car)) {
            return fail('查无改二手车信息');
        }

        return success($car);
    }
}