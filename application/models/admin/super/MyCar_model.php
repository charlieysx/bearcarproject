<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyCar_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_car_list($car_status, $page = 0, $pageSize = 15) 
    {
        $carDB = $this->db->from(TABLE_CAR)
                            ->select('car_id as carId, car_brand.brand_name as brandName, car_series.series_name as seriesName, car_model.model_name as modelName, 
                                    licensed_city.name as licensedCityName, licensed_year as licensedYear, licensed_month as licensedMonth, 
                                    car_condition.condition_name as conditionName, expire_date.expire_date_name as expireDateName, mileage, 
                                    transfer_time as transferTime, car.status as status, publish_time as publishTime, see_count as seeCount, under_reason as underReason,
                                    car.inspect_datetime as checkTimeId, inspect_address as inspectAddress, city.name as cityName,
                                    province.name as provinceName, district.name as districtName, user.phone as phone, deal_user_id as dealUserId')
                            ->join(TABLE_CITY.' as licensed_city', 'licensed_city.id = car.licensed_city_id')
                            ->join(TABLE_CITY, 'city.id = car.city_id')
                            ->join(TABLE_PROVINCE, 'province.id = car.province_id')
                            ->join(TABLE_DISTRICT, 'district.id = car.district_id')
                            ->join(TABLE_CAR_BRAND, 'car_brand.brand_id = car.brand_id')
                            ->join(TABLE_CAR_SERIES, 'car_series.series_id = car.series_id', 'LEFT')
                            ->join(TABLE_CAR_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(TABLE_CAR_CONDITION, 'car_condition.condition_id = car.car_condition_id')
                            ->join(TABLE_EXPIRE_DATE, 'expire_date.expire_date_id = car.expire_date_id')
                            ->join(TABLE_USER, 'user.user_id = car.user_id')
                            ->group_start()
                              ->where('car.status', $car_status);
        if($car_status == 2) {
          $carDB->or_where('car.status', 4)->or_where('car.status', 5);
        }
        $carDB->group_end();
        $car = $carDB->limit($pageSize, $page*$pageSize)->order_by('publish_time', 'DESC')->get()->result_array();

        for($i = 0;$i < count($car);$i++) {
          if($car[$i]['modelName'] == null) {
            unset($car[$i]['modelName']);
          }
          if($car[$i]['seriesName'] == null) {
              $car[$i]['seriesName'] = '';
          }
          if($car[$i]['status'] != 5) {
              unset($car[$i]['underReason']);
          }
          if($car[$i]['dealUserId'] == null) {
              unset($car[$i]['dealUserId']);
          }
          if($car_status != 1 && $car_status != 2 && $car_status != 6) {
              unset($car[$i]['inspectAddress']);
              unset($car[$i]['phone']);
          }
        }
        return success($car);
    }

    public function get_mycar_count($car_status) {
        $count_all = $this->db->from(TABLE_CAR)
                                ->group_start()
                                  ->where('status', $car_status);
        if($car_status == 3) {
          $count_all->or_where('status', 4)->or_where('status', 5);
        }
        $count_all = $count_all->group_end()->count_all_results();
        return success($count_all);
    }

    public function under($user_id, $car_id, $under_reason) {
        $car = $this->db->from(TABLE_CAR)
                          ->where('car_id', $car_id)
                          ->get()
                          ->row_array();
        if(empty($car)) {
            return fail('没有该辆车信息');
        }

        if($car['status'] == 3 || $car['status'] == 4 || $car['status'] == 5) {
            return fail('该辆二手车的状态不能下架');
        }

        //已经有订单生成，下架需要修改订单状态
        if($car['status'] == 1 || $car['status'] == 2 || $car['status'] == 6) {
            $order = array(
                'status'=> 4,
                'finish_time'=> time()
            );
            $this->db->where('car_id', $car_id)->update(TABLE_ORDER, $order);
        }

        $newStatus = array(
            'status'=> 5,
            'under_reason' => $under_reason,
            'under_user_id'=> $user_id
        );
        $this->db->where('car_id', $car_id)->update(TABLE_CAR, $newStatus);

        return success('下架成功');
    }
}