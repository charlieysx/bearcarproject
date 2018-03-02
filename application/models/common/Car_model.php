<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Car_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_car_info($car_id) {
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
        
        $data['carImage']['imgEngineChassis'] = json_decode($data['carImage']['imgEngineChassis']);
        $data['carImage']['imgOut'] = json_decode($data['carImage']['imgOut']);
        $data['carImage']['imgIn'] = json_decode($data['carImage']['imgIn']);

        
        return success($data);
    }
}