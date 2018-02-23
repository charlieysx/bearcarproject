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
                                    city.name as cityName, licensed_year as licensedYear, licensed_month as licensedMonth, 
                                    car_condition.condition_name as conditionName, expire_date.expire_date_name as expireDateName, mileage, 
                                    transfer_time as transferTime, status, publish_time as publishTime, see_count as seeCount')
                            ->join(self::TABLE_CITY, 'city.id = car.licensed_city_id')
                            ->join(self::TABLE_BRAND, 'car_brand.brand_id = car.brand_id')
                            ->join(self::TABLE_SERIES, 'car_series.series_id = car.series_id')
                            ->join(self::TABLE_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(self::TABLE_CONDITION, 'car_condition.condition_id = car.car_condition_id')
                            ->join(self::TABLE_EXPIRE_DATE, 'expire_date.expire_date_id = car.expire_date_id')
                            ->where('user_id', $user_id)
                            ->where('status', $car_status);
        if($car_status == 3) {
          $car_db = $car_db->or_where('status', 4)->or_where('status', 5);
        }
        $car = $car_db->limit($pageSize, $page*$pageSize)->order_by('publish_time', 'DESC')->get()->result_array();
        $count_all = $this->db->from(self::TABLE_CAR)
                              ->where('user_id', $user_id)
                              ->where('status', $car_status);
        if($car_status == 3) {
            $count_all = $count_all->or_where('status', 4)->or_where('status', 5);
        }
        $count_all = $count_all->count_all_results();

        $result = array(
          'type'=> $type,
          'page'=> $page,
          'pageSize'=> $pageSize,
          'sizeAll'=> $count_all,
          'list'=> $car
        );
        return success_result('查询成功', $result);
    }

    public function under($user_id, $car_id) {
        $car = $this->db->from(self::TABLE_CAR)
                          ->where('user_id', $user_id)
                          ->where('car_id', $car_id)
                          ->get()
                          ->row_array();
        if(empty($car)) {
          return success_result('没有该辆车信息或该辆车不属于您');
        }

        if($car.status != 0) {
          return success_result('该辆二手车的状态不能下架');
        }

        $newStatus = array(
          'status'=> 5
        );
        $this->db->where('car_id', $car_id)->update(self::TABLE_CAR, $newStatus);

        return success_result('下架成功');
    }
}