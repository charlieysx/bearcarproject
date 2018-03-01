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

    public function fill_car_first_step($user_id, $params) {
        $info = $this->db->from(TABLE_ORDER)
                            ->select('car.status as carStatus, order_id, order.status as orderStatus, order.step as orderStep,')
                            ->join(TABLE_CAR, 'car.car_id = order.car_id')
                            ->where('order.car_id', $params['carId'])
                            ->where('appraiser_id', $user_id)
                            ->get()->row_array();
        if(empty($info)) {
            return fail('查无改二手车信息或您无权上传该二手车信息');
        }

        if($info['carStatus'] != '6' || $info['orderStatus'] != '1') {
            return fail('该辆二手车的信息不能修改');
        }

        if($info['orderStep'] != '1') {
            return fail('该辆二手车的配置信息已上传，不能再上传');
        }

        $order = array(
            'step'=> '2'
        );
        //更新订单的检测完成步骤
        $this->db->where('order_id', $info['order_id'])->update(TABLE_ORDER, $order);

        $infoBase = array(
            'car_id'=> $params['carId'],
            'price'=> $params['baseInfo']['ownerPrice'],
            'new_car_price'=> $params['baseInfo']['newCarPrice'],
            'tax'=> $params['baseInfo']['tax'],
            'licensed_year'=> $params['baseInfo']['licensedYear'],
            'licensed_month'=> $params['baseInfo']['licensedMonth'],
            'mileage'=> $params['baseInfo']['mileage'],
            'licensed_city_id'=> $params['baseInfo']['licensedCity'],
            'user_name'=> $params['baseInfo']['userName'],
            'look_city_id'=> $params['baseInfo']['lookCity'],
            'year_check'=> $params['baseInfo']['checkYear'],
            'strong_risk'=> $params['baseInfo']['strongRiskYear'],
            'business_rish'=> $params['baseInfo']['businessRiskYear'],
            'car_options'=> $params['baseInfo']['carOptions'],
            'transfer_time'=> $params['baseInfo']['transferTime']
        );
        // 基础信息
        $this->db->insert(TABLE_INFO_BASE, $infoBase);

        // $configBase = array(
        //     'car_id'=> $params['carId'],
        //     'credentials'=> $params['configBase']['credentials'],
        //     'vendor'=> $params['configBase']['vendor'],
        //     'level'=> $params['configBase']['level'],
        //     'engine'=> $params['configBase']['engine'],
        //     'speed'=> $params['configBase']['speed'],
        //     'structure'=> $params['configBase']['structure'],
        //     'length'=> $params['configBase']['length'],
        //     'width'=> $params['configBase']['width'],
        //     'height'=> $params['configBase']['height'],
        //     'wheelbase'=> $params['configBase']['wheelbase'],
        //     'trunk'=> $params['configBase']['trunk'],
        //     'quality'=> $params['configBase']['quality']
        // );
        // // 基础参数
        // $this->db->insert(TABLE_CONFIG_BASE, $configBase);

        // $configEngine = array(
        //     'car_id'=> $params['carId'],
        //     'displacement'=> $params['configEngine']['displacement'],
        //     'air_intake'=> $params['configEngine']['airIntake'],
        //     'cylinder'=> $params['configEngine']['cylinder'],
        //     'max_horsepower'=> $params['configEngine']['maxHorsepower'],
        //     'max_torque'=> $params['configEngine']['maxTorque'],
        //     'fuel_type'=> $params['configEngine']['fuelType'],
        //     'fuel_label'=> $params['configEngine']['fuelLabel'],
        //     'oil_supply_way'=> $params['configEngine']['oilSupplyWay'],
        //     'emission_standards'=> $params['configEngine']['emissionStandards']
        // );
        // // 发动机参数
        // $this->db->insert(TABLE_CONFIG_ENGINE, $configEngine);

        // $configChassisBrake = array(
        //     'car_id'=> $params['carId'],
        //     'drive_mode'=> $params['configChassisBrake']['driveMode'],
        //     'power_type'=> $params['configChassisBrake']['powerType'],
        //     'suspension_front'=> $params['configChassisBrake']['suspensionFront'],
        //     'suspension_behind'=> $params['configChassisBrake']['suspensionBehind'],
        //     'brake_front'=> $params['configChassisBrake']['brakeFront'],
        //     'brake_behind'=> $params['configChassisBrake']['brakeBehind'],
        //     'parking_brake'=> $params['configChassisBrake']['parkingBrake'],
        //     'tire_size_front'=> $params['configChassisBrake']['tireSizeFront'],
        //     'tire_size_behind'=> $params['configChassisBrake']['tireSizeBehind']
        // );
        // // 底盘与制动参数
        // $this->db->insert(TABLE_CONFIG_CHASSIS_BRAKE, $configChassisBrake);

        // $configSafety = array(
        //     'car_id'=> $params['carId'],
        //     'safety_airbag_main'=> $params['configSafety']['safetyAirbagMain']['value'],
        //     'safety_airbag_vice'=> $params['configSafety']['safetyAirbagVice']['value'],
        //     'side_airbag_front'=> $params['configSafety']['sideAirbagFront']['value'],
        //     'side_airbag_behind'=> $params['configSafety']['sideAirbagBehind']['value'],
        //     'head_airbag_front'=> $params['configSafety']['headAirbagFront']['value'],
        //     'head_airbag_behind'=> $params['configSafety']['headAirbagBehind']['value'],
        //     'tire_pressure_monitoring'=> $params['configSafety']['tirePressureMonitoring']['value'],
        //     'in_control_lock'=> $params['configSafety']['inControlLock']['value'],
        //     'child_seat_interface'=> $params['configSafety']['childSeatInterface']['value'],
        //     'keyless_start'=> $params['configSafety']['keylessStart']['value'],
        //     'abs'=> $params['configSafety']['abs']['value'],
        //     'esp'=> $params['configSafety']['esp']['value']
        // );
        // // 安全配置参数
        // $this->db->insert(TABLE_CONFIG_SAFETY, $configSafety);

        // $configOut = array(
        //     'car_id'=> $params['carId'],
        //     'skylight_electric'=> $params['configOut']['skylightElectric']['value'],
        //     'skylight_full_view'=> $params['configOut']['skylightFullView']['value'],
        //     'electric_suction_door'=> $params['configOut']['electricSuctionDoor']['value'],
        //     'induction_trunk'=> $params['configOut']['inductionTrunk']['value'],
        //     'windshield_wiper_sensing'=> $params['configOut']['windshieldWiperSensing']['value'],
        //     'windshield_wiper_behind'=> $params['configOut']['windshieldWiperBehind']['value'],
        //     'electric_window_front'=> $params['configOut']['electricWindowFront']['value'],
        //     'electric_window_behind'=> $params['configOut']['electricWindowBehind']['value'],
        //     'rear_view_mirror_electric'=> $params['configOut']['rearViewMirrorElectric']['value'],
        //     'rear_view_mirror_hot'=> $params['configOut']['rearViewMirrorHot']['value']
        // );
        // // 外部配置参数
        // $this->db->insert(TABLE_CONFIG_OUT, $configOut);

        // $configIn = array(
        //     'car_id'=> $params['carId'],
        //     'multi_steering_wheel'=> $params['configIn']['multiSteeringWheel']['value'],
        //     'cruise_control'=> $params['configIn']['cruiseControl']['value'],
        //     'air_conditioner'=> $params['configIn']['airConditioner']['value'],
        //     'air_conditioner_auto'=> $params['configIn']['airConditionerAuto']['value'],
        //     'gps'=> $params['configIn']['gps']['value'],
        //     'reversing_radar'=> $params['configIn']['reversingRadar']['value'],
        //     'reversing_image_system'=> $params['configIn']['reversingImageSystem']['value'],
        //     'leather_seat'=> $params['configIn']['leatherSeat']['value'],
        //     'seat_hot_front'=> $params['configIn']['seatHotFront']['value'],
        //     'sear_hot_behind'=> $params['configIn']['searHotBehind']['value']
        // );
        // // 内部配置参数
        // $this->db->insert(TABLE_CONFIG_IN, $configIn);

        return success('添加完成');
    }
}