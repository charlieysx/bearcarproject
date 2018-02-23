<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyCar_model extends Base_Model
{

    const TABLE_CAR = 'car';
    const TABLE_CITY = 'city';
    const TABLE_BRAND = 'car_brand';
    const TABLE_SERIES = 'car_series';
    const TABLE_MODEL = 'car_model';
    const TABLE_EXPIRE_DATE = 'expire_date';
    const TABLE_CONDITION = 'car_condition';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_my_car($user_id, $type, $page = 0, $pageSize = 15) 
    {
        $car_status = 0;
        switch($type) {
          case 'waiting':
            $car_status = 0;
            break;
          case 'selling':
            $car_status = 1;
            break;
          case 'ordering':
            $car_status = 2;
            break;
          case 'under':
            $car_status = 3;
            break;
          default:
            $type = 'waiting';
            $car_status = 0;
            break;
        }

        $car_db = $this->db->from(self::TABLE_CAR)
                            ->select('car_id as carId, car_brand.brand_name as brandName, car_series.series_name as seriesName, car_model.model_name as modelName, 
                                    city.name as cityName, licensed_year, licensed_month, 
                                    car_condition.condition_name as conditionName, expire_date.expire_date_name as expireDateName, mileage, 
                                    transfer_time, status, publish_time, see_count')
                            ->join(self::TABLE_CITY, 'city.id = car.licensed_city_id')
                            ->join(self::TABLE_BRAND, 'car_brand.brand_id = car.brand_id')
                            ->join(self::TABLE_SERIES, 'car_series.series_id = car.series_id')
                            ->join(self::TABLE_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(self::TABLE_CONDITION, 'car_condition.condition_id = car.car_condition_id')
                            ->join(self::TABLE_EXPIRE_DATE, 'expire_date.expire_date_id = car.expire_date_id')
                            ->where('user_id', $user_id);
        if($car_status == 3) {
          $car_db = $car_db->or_group_start()
                            ->where('status', 3)
                            ->or_where('status', 4)
                           ->group_end();
        } else {
          $car_db = $car_db->where('status', $car_status);
        }
        $car = $car_db->limit($pageSize, $page)->get()->result_array();

        $result = array(
          'type'=> $type,
          'page'=> $page,
          'pageSize'=> $pageSize,
          'list'=> $car
        );
        return success_result('查询成功', $result);
    }
}