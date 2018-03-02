<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyCar_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_car_list($user_id, $car_status, $page = 0, $pageSize = 15) 
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
                            ->join(TABLE_CAR_SERIES, 'car_series.series_id = car.series_id')
                            ->join(TABLE_CAR_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(TABLE_CAR_CONDITION, 'car_condition.condition_id = car.car_condition_id')
                            ->join(TABLE_EXPIRE_DATE, 'expire_date.expire_date_id = car.expire_date_id')
                            ->join(TABLE_USER, 'user.user_id = car.user_id')
                            ->group_start()
                              ->where('car.status', $car_status);
        if($car_status == 3) {
          $carDB->or_where('car.status', 4)->or_where('car.status', 5);
        }
        $carDB->group_end();
        //查的不是待上架的，就需要附带userid
        if($car_status != 0) {
          $carDB->group_start()
                  ->where('under_user_id', $user_id)
                  ->or_where('deal_user_id', $user_id)
                ->group_end();
        }
        $car = $carDB->limit($pageSize, $page*$pageSize)->order_by('publish_time', 'DESC')->get()->result_array();

        for($i = 0;$i < count($car);$i++) {
            if($car[$i]['modelName'] == null) {
                unset($car[$i]['modelName']);
            }
            if($car[$i]['status'] != 5) {
                unset($car[$i]['underReason']);
            }
            if($car_status != 1 && $car_status != 2 && $car_status != 6) {
                unset($car[$i]['inspectAddress']);
                unset($car[$i]['phone']);
            }
        }
        return success($car);
    }

    public function get_mycar_count($user_id, $car_status) {
        $carDB = $this->db->from(TABLE_CAR)
                                ->group_start()
                                  ->where('status', $car_status);
        if($car_status == 3) {
          $carDB->or_where('status', 4)->or_where('status', 5);
        }
        $carDB->group_end();
         //查的不是待上架的，就需要附带userid
        if($car_status != 0) {
          $carDB->group_start()
                  ->where('under_user_id', $user_id)
                  ->or_where('deal_user_id', $user_id)
                ->group_end();
        }
        $count_all = $carDB->count_all_results();
        return success($count_all);
    }

    public function under($user_id, $car_id, $under_reason) {
        $car = $this->db->from(TABLE_CAR)
                          ->where('car_id', $car_id)
                          ->group_start()
                            ->where('under_user_id', $user_id)
                            ->or_where('deal_user_id', $user_id)
                            ->or_where('status', '0')
                          ->group_end()
                          ->get()
                          ->row_array();
        if(empty($car)) {
            return fail('没有该辆车或您没有权限下架该辆车');
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
            'under_reason'=> $under_reason,
            'under_user_id'=> $user_id
        );
        $this->db->where('car_id', $car_id)->update(TABLE_CAR, $newStatus);

        return success('下架成功');
    }

    public function order_check($user_id, $car_id) {
        $car = $this->db->from(TABLE_CAR)
                          ->where('car_id', $car_id)
                          ->get()
                          ->row_array();
        if(empty($car)) {
            return fail('没有该辆车');
        }

        if($car['status'] != 0) {
            return fail('该辆二手车已被接或已被下架，您不能预约检测');
        }

        $newStatus = array(
            'status'=> 6,
            'deal_user_id'=> $user_id
        );
        $this->db->where('car_id', $car_id)->update(TABLE_CAR, $newStatus);

        $orderId = create_car_id($car_id);
        $orderParam = array(
            'order_id'=> $orderId,
            'car_id'=> $car_id,
            'appraiser_id'=> $user_id,
            'start_time'=> time(),
            'order_number'=> $orderId
        );
        // 添加数据
        $this->db->insert(TABLE_ORDER, $orderParam);

        $order = $this->db->from(TABLE_ORDER)
                          ->where('order_id', $orderId)
                          ->get()
                          ->row_array();

        $orderNumber = 'BC'.date("YmdHis", $order['start_time']).$order['id'];

        $number = array(
            'order_number'=> $orderNumber
        );
        $this->db->where('order_id', $orderId)->update(TABLE_ORDER, $number);

        return success('预约成功，您可在 待检测列表 查看更多信息');
    }

    public function get_mycar_info($user_id, $car_id) {
        $order = $this->db->from(TABLE_ORDER)
                            ->where('car_id', $car_id)
                            ->get()
                            ->row_array();
        if(empty($order)) {
            return fail('没有该辆车');
        }

        if($order['step'] != '4') {
            return fail('该辆车还没录入详细信息');
        }

        $data = array();

        $carBaseInfo = $this->db->from(TABLE_INFO_BASE)
                                ->select('price, new_car_price as newCarPrice, licensed_year as licensedYear, licensed_month as licenedMonth,
                                        mileage, city.name as licensedCityName, user_name as userName, district.name as lookDistrictName,
                                        year_check as yearCheck, strong_risk as strongRisk, business_risk as businessRisk,
                                        car_options as carOptions, transfer_time as transferTime')
                                ->where('car_id', $car_id)
                                ->join(TABLE_CITY, 'city.id = info_base.licensed_city_id')
                                ->join(TABLE_DISTRICT, 'district.id = info_base.look_district_id')
                                ->get()
                                ->row_array();
        
        $data['carBaseInfo'] = $carBaseInfo;

        $configTable = array(
            TABLE_CONFIG_BASE=> array(
                'value'=> 'credentials, vendor, level, engine, speed, structure, length, width, height, wheelbase, trunk, quality',
                'name'=> 'configBase'
            ),
            TABLE_CONFIG_CHASSIS_BRAKE=> array(
                'value'=> 'drive_mode as driveMode, power_type as powerType, suspension_front as suspensionFront, suspension_behind as suspensionBehind,
                          brake_front as brakeFront, brake_behind as brakeBehind, parking_brake as parkingBrake, 
                          tire_size_front as tireSizeFront, tire_size_behind as tireSizeBehind', 
                'name'=> 'configChassisBrake'
            ),
            TABLE_CONFIG_ENGINE=> array(
                'value'=> 'displacement, air_intake as airIntake, cylinder, max_horsepower as maxHorsePower, max_torque as maxTorque,
                            fuel_type as fuelType, fuel_label as fuelLabel, oil_supply_way as oilSupplyWay, emission_standards as emissionStandards',
                'name'=> 'configEngine'
            ),
            TABLE_CONFIG_IN=> array(
                'value'=> 'multi_steering_wheel as multiSteeringWheel, cruise_control as cruiseControl, air_conditioner as airConditioner,
                            air_conditioner_auto as airConditioner, gps, reversing_radar as reversingRadar, reversing_image_system as reversingImageSystem,
                            leather_seat as leatherSeat, seat_hot_front as seatHotFront, seat_hot_behind as seatHotBehind',
                'name'=> 'configIn'
            ),
            TABLE_CONFIG_OUT=> array(
                'value'=> 'skylight_electric as skylightElectric, skylight_full_view as skylightFullView, electric_suction_door as electricSuctionDoor,
                            induction_trunk as inductionTrunk, windshield_wiper_sensing as windshieldWiperSensing, windshield_wiper_behind as windshieldWiperBehind,
                            electric_window_front as electricWindowFront, electric_window_behind as electricWindowBehind, rear_view_mirror_electric rearViewMirrorElectric,
                            rear_view_mirror_hot as rearViewMirrorHot',
                'name'=> 'configOut'
            ),
            TABLE_CONFIG_SAFETY=> array(
                'value'=> 'safety_airbag_main as safetyAirbagMain, safety_airbag_vice as safetyAirbagVic, side_airbag_front as sideAirbagFront,
                                side_airbag_behind as sideAirbagBehind, head_airbag_front as headAirbagFront, head_airbag_behind as headAirbagBehind,
                                tire_pressure_monitoring as tirePressureMonitoring, in_control_lock as inControlLock, child_seat_interface as childSeatInterface,
                                keyless_start as keylessStart, abs, esp',
                'name'=> 'configSafety'
            ),
        );

        foreach($configTable as $k => $v) {
            $data[$v['name']] = $this->db->from($k)
                                    ->select($v['value'])
                                    ->where('car_id', $car_id)
                                    ->get()
                                    ->row_array();
        }

        $checkTable = array(
            TABLE_CHECK_ACCIDENT=> 'checkAccident',
            TABLE_CHECK_APPEARANCE=> 'checkAppearance',
            TABLE_CHECK_BREAKABLE_PART=> 'checkBreakablePart',
            TABLE_CHECK_CRASH=> 'checkCrash',
            TABLE_CHECK_ENGINE_STATUS=> 'checkEngineStatus',
            TABLE_CHECK_HIGH_TECH=> 'checkHighTech',
            TABLE_CHECK_IN_CONFIG=> 'checkInConfig',
            TABLE_CHECK_INSTRUMENT_DESK=> 'checkInstrumentDesk',
            TABLE_CHECK_LIGHT_SYSTEM=> 'checkLightSystem',
            TABLE_CHECK_OUT_CONFIG=> 'checkOutConfig',
            TABLE_CHECK_SAFETY_SYSTEM=> 'checkSafetySystem',
            TABLE_CHECK_SPEED=> 'checkSpeed',
            TABLE_CHECK_TOOL=> 'checkTool',
            TABLE_CHECK_WATER_FIRE=> 'checkWaterFire'
        );

        foreach($checkTable as $k => $v) {
            $data[$v] = $this->db->from($k)
                                    ->select('value, abnormal')
                                    ->where('car_id', $car_id)
                                    ->get()
                                    ->row_array();
            $data[$v]['value'] = json_decode($data[$v]['value']);
        }

        $data['carImage'] = $this->db->from(TABLE_CAR_IMAGE)
                                        ->select('img_out as imgOut, img_in as imgIn, img_engine_chassis as imgEngineChassis, img as coverImg')
                                        ->where('car_id', $car_id)
                                        ->get()
                                        ->row_array();

        
        return success($data);
    }
}