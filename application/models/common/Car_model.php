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
                            ->select('step, order.check_time as checkTime, car_brand.brand_name as brandName,
                                    car_series.series_name as seriesName, car_model.model_name as modelName,
                                    car.status as carStatus')
                            ->where('order.car_id', $car_id)
                            ->join(TABLE_CAR, 'car.car_id = order.car_id')
                            ->join(TABLE_CAR_BRAND, 'car_brand.brand_id = car.brand_id')
                            ->join(TABLE_CAR_SERIES, 'car_series.series_id = car.series_id')
                            ->join(TABLE_CAR_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
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
        $data['carBaseInfo']['checkTime'] = $order['checkTime'];
        $data['carBaseInfo']['brandName'] = $order['brandName'];
        $data['carBaseInfo']['seriesName'] = $order['seriesName'];
        $data['carBaseInfo']['modelName'] = $order['modelName'];
        $data['carBaseInfo']['carStatus'] = $order['carStatus'];

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
                            air_conditioner_auto as airConditionerAuto, gps, reversing_radar as reversingRadar, reversing_image_system as reversingImageSystem,
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
        
        $car = $this->db->from(TABLE_CAR)
                            ->select('see_count')
                            ->where('car_id', $car_id)
                            ->get()
                            ->row_array();
        
        $updateCount = array(
            'see_count' => intval($car['see_count']) + 1
        );

        // 更新数据
        $this->db->where('car_id', $car_id)->update(TABLE_CAR, $updateCount);
        
        return success($data);
    }

    private $CITY_ID = 'cityId';
    private $CAR_ID = 'carId';

    private $SEARCH_VALUE = 'searchValue';
    private $BRAND_ID = 'brandId';
    private $SERIES_ID = 'seriesId';
    private $PRICE = 'price';
    private $CAR_AGE = 'carAge';
    private $SPEED = 'speed';
    private $MODEL = 'model';
    private $MILEAGE = 'mileage';
    private $DISPLACEMENT = 'displacement';
    private $EMISSION_STANDARDS = 'emissionStandards';
    private $SEATING = 'seating';
    private $FUEL_TYPE = 'fuelType';
    private $DRIVING_TYPE = 'drivingType';

    private $SORT = 'sort';

    public function get_car_list($params, $page = 0, $pageSize = 15) {
        $keys = array(
            $this->CITY_ID,
            $this->SEARCH_VALUE,
            $this->BRAND_ID,
            $this->SERIES_ID,
            $this->PRICE,
            $this->CAR_AGE,
            $this->SPEED,
            $this->MODEL,
            $this->MILEAGE,
            $this->DISPLACEMENT,
            $this->EMISSION_STANDARDS,
            $this->SEATING,
            $this->FUEL_TYPE,
            $this->DRIVING_TYPE,
            $this->SORT
        );
        $filter = elements($keys, $params, '');

        $carDB = $this->db->select('car.car_id as carId, car_brand.brand_name as brandName, car_series.series_name as seriesName, car_model.model_name as modelName,
                                    info_base.licensed_year as licensedYear, info_base.mileage, info_base.new_car_price as newCarPrice, city.name as cityName,
                                    info_base.price as price, car_image.img as coverImg, car.expire_date_id as expireDateId, order.check_time as time')
                            ->where('car.status', '1')
                            ->join(TABLE_ORDER, 'car.car_id = order.car_id')
                            ->join(TABLE_CAR_BRAND, 'car.brand_id = car_brand.brand_id')
                            ->join(TABLE_CAR_SERIES, 'car_series.series_id = car.series_id')
                            ->join(TABLE_CAR_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(TABLE_CITY, 'car.licensed_city_id = city.id')
                            ->join(TABLE_INFO_BASE, 'car.car_id = info_base.car_id')
                            ->join(TABLE_CONFIG_BASE, 'car.car_id = config_base.car_id')
                            ->join(TABLE_CONFIG_ENGINE, 'car.car_id = config_engine.car_id')
                            ->join(TABLE_CONFIG_CHASSIS_BRAKE, 'car.car_id = config_chassis_brake.car_id')
                            ->join(TABLE_CAR_IMAGE, 'car_image.car_id = car.car_id');
        
        if($filter[$this->CITY_ID] != '') {
            $carDB->where('car.licensed_city_id', $filter[$this->CITY_ID]);
        }

        if($filter[$this->SEARCH_VALUE] != '') {
            $value = $filter[$this->SEARCH_VALUE];
            $carDB->group_start()
                        ->like('car_brand.brand_name', $value, 'both')
                        ->or_like('car_series.series_name', $value, 'both')
                        ->or_like('car_model.model_name', $value, 'both')
                    ->group_end();
        }

        if($filter[$this->BRAND_ID] != '') {
            $carDB->where('car.brand_id', $filter[$this->BRAND_ID]);
        }

        if($filter[$this->SERIES_ID] != '') {
            $carDB->where('car.series_id', $filter[$this->SERIES_ID]);
        }

        if($filter[$this->PRICE] != '' && $filter[$this->PRICE]['from'] != '' && $filter[$this->PRICE]['to'] != '') {
            $from = floatval($filter[$this->PRICE]['from']);
            $to = floatval($filter[$this->PRICE]['to']);
            $carDB->where('info_base.price >= ', $from)->where('info_base.price <= ', $to);
        }

        if($filter[$this->CAR_AGE] != '') {
            $year = intval(date('Y'));
            $month = intval(date('m'));
            switch($filter[$this->CAR_AGE]) {
                case '1':
                    $year -= 1;
                    break;
                case '2':
                    $year -= 3;
                    break;
                case '3':
                    $year -= 5;
                    break;
                case '4':
                    $year -= 8;
                    break;
                case '5':
                    $year -= 100;
                    break;
            }
            $carDB->group_start()
                        ->where('year(str_to_date(info_base.licensed_year, \'%Y年\')) > ', $year)
                        ->or_group_start()
                            ->where('year(str_to_date(info_base.licensed_year, \'%Y年\')) = ', $year)
                            ->where('month(str_to_date(info_base.licensed_month, \'%m月\')) >= ', $month)
                        ->group_end()
                    ->group_end();
        }

        if($filter[$this->SPEED] != '') {
            $v = $filter[$this->SPEED] == '0' ? '手' : '';
            $carDB->like('config_base.speed', $v, 'both');
        }

        if($filter[$this->MODEL] != '') {
            $v = '';
            switch($filter[$this->MODEL]) {
                case '1':
                    $v = '两';
                    break;
                case '2':
                    $v = '三';
                    break;
                case '3':
                    $v = '跑车';
                    break;
                case '4':
                    $v = '客车';
                    break;
                case '5':
                    $v = 'SUV';
                    break;
                case '6':
                    $v = 'MPV';
                    break;
            }
            $carDB->like('config_base.structure', $v, 'both');
        }

        if($filter[$this->MILEAGE] != '') {
            $v = '';
            switch($filter[$this->MILEAGE]) {
                case '1':
                    $v = 1;
                    break;
                case '2':
                    $v = 3;
                    break;
                case '3':
                    $v = 5;
                    break;
                case '4':
                    $v = 8;
                    break;
                case '5':
                    $v = 10;
                    break;
                case '6':
                    $v = 10;
                    break;
            }
            if ($filter[$this->MILEAGE] == '6') {
                $carDB->where('info_base.mileage > ', $v);
            } else {
                $carDB->where('info_base.mileage <= ', $v);
            }
        }

        if($filter[$this->DISPLACEMENT] != '') {
            $from = 0;
            $to = 100;
            switch($filter[$this->DISPLACEMENT]) {
                case '1':
                    $from = 0;
                    $to = 1;
                    break;
                case '2':
                    $from = 1;
                    $to = 1.6;
                    break;
                case '3':
                    $from = 1.6;
                    $to = 2.0;
                    break;
                case '4':
                    $from = 2.0;
                    $to = 3.0;
                    break;
                case '5':
                    $from = 3.0;
                    $to = 4.0;
                    break;
                case '6':
                    $from = 4.0;
                    $to = 100;
                    break;
            }
            $carDB->where('config_engine.displacement >= ', $from)
                    ->where('config_engine.displacement <= ', $to);
        }

        if($filter[$this->EMISSION_STANDARDS] != '') {
            switch($filter[$this->EMISSION_STANDARDS]) {
                case '1':
                    $carDB->or_group_start()
                            ->where('config_engine.emission_standards', '国二')
                            ->or_where('config_engine.emission_standards', '国三')
                            ->or_where('config_engine.emission_standards', '国四')
                            ->or_where('config_engine.emission_standards', '国五')
                            ->group_end();
                    break;
                case '2':
                    $carDB->or_group_start()
                            ->where('config_engine.emission_standards', '国三')
                            ->or_where('config_engine.emission_standards', '国四')
                            ->or_where('config_engine.emission_standards', '国五')
                            ->group_end();
                    break;
                case '3':
                    $carDB->or_group_start()
                            ->where('config_engine.emission_standards', '国四')
                            ->or_where('config_engine.emission_standards', '国五')
                            ->group_end();
                    break;
                case '4':
                    $carDB->where('config_engine.emission_standards', '国五');
                    break;
            }
        }

        if($filter[$this->SEATING] != '') {
            $v = '';
            switch($filter[$this->SEATING]) {
                case '1':
                    $v = '2';
                    break;
                case '2':
                    $v = '4';
                    break;
                case '3':
                    $v = '5';
                    break;
                case '4':
                    $v = '6';
                    break;
                case '5':
                    $v = '7';
                    break;
            }
            $carDB->like('config_base.structure', $v, 'both');
        }

        if($filter[$this->FUEL_TYPE] != '') {
            $v = '';
            switch($filter[$this->FUEL_TYPE]) {
                case '1':
                    $v = '汽';
                    break;
                case '2':
                    $v = '柴';
                    break;
                case '3':
                    $v = '电';
                    break;
                case '4':
                    $v = '混';
                    break;
            }
            $carDB->like('config_engine.fuel_type', $v, 'both');
        }

        if($filter[$this->DRIVING_TYPE] != '') {
            $v = '';
            switch($filter[$this->DRIVING_TYPE]) {
                case '1':
                    $carDB->not_like('config_chassis_brake.drive_mode', '四');
                    break;
                case '2':
                    $carDB->like('config_chassis_brake.drive_mode', '四', 'both');
                    break;
            }
        }

        if($filter[$this->SORT] != '') {
            $type = $filter[$this->SORT]['type'];
            switch($type) {
                case 'default':
                    $carDB->order_by('car.expire_date_id', 'ASC')
                            ->order_by('order.check_time', 'DESC');
                    break;
                case 'new':
                    $carDB->order_by('order.check_time', 'DESC');
                    break;
                case 'price':
                    $v = $filter[$this->SORT]['value'] == '0' ? 'ASC' : 'DESC';
                    $carDB->order_by('info_base.price', $v);
                    break;
                case 'age':
                    $v = $filter[$this->SORT]['value'] == '1' ? 'ASC' : 'DESC';
                    $carDB->order_by('info_base.licensed_year', $v);
                    break;
                case 'mileage':
                    $v = $filter[$this->SORT]['value'] == '0' ? 'ASC' : 'DESC';
                    $carDB->order_by('info_base.mileage', $v);
                    break;
            }
        } else {
            $carDB->order_by('car.expire_date_id', 'ASC')
                    ->order_by('order.check_time', 'DESC');
        }
    
        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $carDB->count_all_results(TABLE_CAR, FALSE),
            'list'=> $carDB->limit($pageSize, $page*$pageSize)->get()->result_array()
        );

        $this->record($filter);

        return success($data);
    }

    private function record($filter) {
        if($filter[$this->CITY_ID] != '') {
            $c = $this->db->from(TABLE_CITY)
                            ->select('search_count')
                            ->where('id', $filter[$this->CITY_ID])
                            ->get()
                            ->row_array();
            $d = array(
                'search_count'=> intval($c['search_count']) + 1
            );
            $this->db->where('id', $filter[$this->CITY_ID])->update(TABLE_CITY, $d);
        }

        if($filter[$this->BRAND_ID] != '') {
            $c = $this->db->from(TABLE_CAR_BRAND)
                            ->select('search_count')
                            ->where('brand_id', $filter[$this->BRAND_ID])
                            ->get()
                            ->row_array();

            $d = array(
                'search_count'=> intval($c['search_count']) + 1
            );
            $this->db->where('brand_id', $filter[$this->BRAND_ID])->update(TABLE_CAR_BRAND, $d);
        }

        if($filter[$this->SERIES_ID] != '') {
            $c = $this->db->from(TABLE_CAR_SERIES)
                            ->select('search_count')
                            ->where('series_id', $filter[$this->SERIES_ID])
                            ->get()
                            ->row_array();
            $d = array(
                'search_count'=> intval($c['search_count']) + 1
            );
            $this->db->where('series_id', $filter[$this->SERIES_ID])->update(TABLE_CAR_SERIES, $d);
        }
    }

    public function get_like_list($params, $page = 0, $pageSize = 15) {
        $keys = array(
            $this->CAR_ID,
            $this->CITY_ID,
            $this->SEARCH_VALUE,
            $this->BRAND_ID,
            $this->SERIES_ID,
            $this->PRICE,
            $this->CAR_AGE,
            $this->SPEED,
            $this->MODEL,
            $this->MILEAGE,
            $this->DISPLACEMENT,
            $this->EMISSION_STANDARDS,
            $this->SEATING,
            $this->FUEL_TYPE,
            $this->DRIVING_TYPE,
            $this->SORT
        );
        $filter = elements($keys, $params, '');

        $carDB = $this->db->select('car.car_id as carId, car_brand.brand_name as brandName, car_series.series_name as seriesName, car_model.model_name as modelName,
                                    info_base.licensed_year as licensedYear, info_base.mileage, info_base.new_car_price as newCarPrice, city.name as cityName,
                                    info_base.price as price, car_image.img as coverImg, car.expire_date_id as expireDateId, order.check_time as time')
                            ->where('car.status', '1')
                            ->join(TABLE_ORDER, 'car.car_id = order.car_id')
                            ->join(TABLE_CAR_BRAND, 'car.brand_id = car_brand.brand_id')
                            ->join(TABLE_CAR_SERIES, 'car_series.series_id = car.series_id')
                            ->join(TABLE_CAR_MODEL, 'car_model.model_id = car.model_id', 'LEFT')
                            ->join(TABLE_CITY, 'car.licensed_city_id = city.id')
                            ->join(TABLE_INFO_BASE, 'car.car_id = info_base.car_id')
                            ->join(TABLE_CONFIG_BASE, 'car.car_id = config_base.car_id')
                            ->join(TABLE_CONFIG_ENGINE, 'car.car_id = config_engine.car_id')
                            ->join(TABLE_CONFIG_CHASSIS_BRAKE, 'car.car_id = config_chassis_brake.car_id')
                            ->join(TABLE_CAR_IMAGE, 'car_image.car_id = car.car_id');
        
        if($filter[$this->CAR_ID] != '') {
            $carDB->where('car.car_id != ', $filter[$this->CAR_ID]);
        }

        if($filter[$this->CITY_ID] != '') {
            $carDB->where('car.licensed_city_id', $filter[$this->CITY_ID]);
        }

        if($filter[$this->SEARCH_VALUE] != '') {
            $value = $filter[$this->SEARCH_VALUE];
            $carDB->group_start()
                        ->like('car_brand.brand_name', $value, 'both')
                        ->or_like('car_series.series_name', $value, 'both')
                        ->or_like('car_model.model_name', $value, 'both')
                    ->group_end();
        }

        if($filter[$this->BRAND_ID] != '') {
            $carDB->where('car.brand_id', $filter[$this->BRAND_ID]);
        }

        if($filter[$this->SERIES_ID] != '') {
            $carDB->where('car.series_id', $filter[$this->SERIES_ID]);
        }

        if($filter[$this->PRICE] != '' && $filter[$this->PRICE]['from'] != '' && $filter[$this->PRICE]['to'] != '') {
            $from = floatval($filter[$this->PRICE]['from']);
            $to = floatval($filter[$this->PRICE]['to']);
            $carDB->where('info_base.price >= ', $from)->where('info_base.price <= ', $to);
        }

        if($filter[$this->CAR_AGE] != '') {
            $year = intval(date('Y'));
            $month = intval(date('m'));
            switch($filter[$this->CAR_AGE]) {
                case '1':
                    $year -= 1;
                    break;
                case '2':
                    $year -= 3;
                    break;
                case '3':
                    $year -= 5;
                    break;
                case '4':
                    $year -= 8;
                    break;
                case '5':
                    $year -= 100;
                    break;
            }
            $carDB->group_start()
                        ->where('year(str_to_date(info_base.licensed_year, \'%Y年\')) > ', $year)
                        ->or_group_start()
                            ->where('year(str_to_date(info_base.licensed_year, \'%Y年\')) = ', $year)
                            ->where('month(str_to_date(info_base.licensed_month, \'%m月\')) >= ', $month)
                        ->group_end()
                    ->group_end();
        }

        if($filter[$this->SPEED] != '') {
            $v = $filter[$this->SPEED] == '0' ? '手' : '';
            return success($v);
            $carDB->like('config_base.speed', $v, 'both');
        }

        if($filter[$this->MODEL] != '') {
            $v = '';
            switch($filter[$this->MODEL]) {
                case '1':
                    $v = '两';
                    break;
                case '2':
                    $v = '三';
                    break;
                case '3':
                    $v = '跑车';
                    break;
                case '4':
                    $v = '客车';
                    break;
                case '5':
                    $v = 'SUV';
                    break;
                case '6':
                    $v = 'MPV';
                    break;
            }
            $carDB->like('config_base.structure', $v, 'both');
        }

        if($filter[$this->MILEAGE] != '') {
            $v = '';
            switch($filter[$this->MILEAGE]) {
                case '1':
                    $v = 1;
                    break;
                case '2':
                    $v = 3;
                    break;
                case '3':
                    $v = 5;
                    break;
                case '4':
                    $v = 8;
                    break;
                case '5':
                    $v = 10;
                    break;
                case '6':
                    $v = 10;
                    break;
            }
            if ($filter[$this->MILEAGE] == '6') {
                $carDB->where('info_base.mileage > ', $v);
            } else {
                $carDB->where('info_base.mileage <= ', $v);
            }
        }

        if($filter[$this->DISPLACEMENT] != '') {
            $from = 0;
            $to = 100;
            switch($filter[$this->DISPLACEMENT]) {
                case '1':
                    $from = 0;
                    $to = 1;
                    break;
                case '2':
                    $from = 1;
                    $to = 1.6;
                    break;
                case '3':
                    $from = 1.6;
                    $to = 2.0;
                    break;
                case '4':
                    $from = 2.0;
                    $to = 3.0;
                    break;
                case '5':
                    $from = 3.0;
                    $to = 4.0;
                    break;
                case '6':
                    $from = 4.0;
                    $to = 100;
                    break;
            }
            $carDB->where('config_engine.displacement >= ', $from)
                    ->where('config_engine.displacement <= ', $to);
        }

        if($filter[$this->EMISSION_STANDARDS] != '') {
            switch($filter[$this->EMISSION_STANDARDS]) {
                case '1':
                    $carDB->or_group_start()
                            ->where('config_engine.emission_standards', '国二')
                            ->or_where('config_engine.emission_standards', '国三')
                            ->or_where('config_engine.emission_standards', '国四')
                            ->or_where('config_engine.emission_standards', '国五')
                            ->group_end();
                    break;
                case '2':
                    $carDB->or_group_start()
                            ->where('config_engine.emission_standards', '国三')
                            ->or_where('config_engine.emission_standards', '国四')
                            ->or_where('config_engine.emission_standards', '国五')
                            ->group_end();
                    break;
                case '3':
                    $carDB->or_group_start()
                            ->where('config_engine.emission_standards', '国四')
                            ->or_where('config_engine.emission_standards', '国五')
                            ->group_end();
                    break;
                case '4':
                    $carDB->where('config_engine.emission_standards', '国五');
                    break;
            }
        }

        if($filter[$this->SEATING] != '') {
            $v = '';
            switch($filter[$this->SEATING]) {
                case '1':
                    $v = '2';
                    break;
                case '2':
                    $v = '4';
                    break;
                case '3':
                    $v = '5';
                    break;
                case '4':
                    $v = '6';
                    break;
                case '5':
                    $v = '7';
                    break;
            }
            $carDB->like('config_base.structure', $v, 'both');
        }

        if($filter[$this->FUEL_TYPE] != '') {
            $v = '';
            switch($filter[$this->FUEL_TYPE]) {
                case '1':
                    $v = '汽';
                    break;
                case '2':
                    $v = '柴';
                    break;
                case '3':
                    $v = '电';
                    break;
                case '4':
                    $v = '混';
                    break;
            }
            $carDB->like('config_engine.fuel_type', $v, 'both');
        }

        if($filter[$this->DRIVING_TYPE] != '') {
            $v = '';
            switch($filter[$this->DRIVING_TYPE]) {
                case '1':
                    $carDB->not_like('config_chassis_brake.drive_mode', '四');
                    break;
                case '2':
                    $carDB->like('config_chassis_brake.drive_mode', '四', 'both');
                    break;
            }
        }

        if($filter[$this->SORT] != '') {
            $type = $filter[$this->SORT]['type'];
            switch($type) {
                case 'default':
                    $carDB->order_by('car.expire_date_id', 'ASC')
                            ->order_by('order.check_time', 'DESC');
                    break;
                case 'new':
                    $carDB->order_by('order.check_time', 'DESC');
                    break;
                case 'price':
                    $v = $filter[$this->SORT]['value'] == '0' ? 'ASC' : 'DESC';
                    $carDB->order_by('info_base.price', $v);
                    break;
                case 'age':
                    $v = $filter[$this->SORT]['value'] == '1' ? 'ASC' : 'DESC';
                    $carDB->order_by('info_base.licensed_year', $v);
                    break;
                case 'mileage':
                    $v = $filter[$this->SORT]['value'] == '0' ? 'ASC' : 'DESC';
                    $carDB->order_by('info_base.mileage', $v);
                    break;
            }
        } else {
            $carDB->order_by('car.expire_date_id', 'ASC')
                    ->order_by('order.check_time', 'DESC');
        }
    
        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $carDB->count_all_results(TABLE_CAR, FALSE),
            'list'=> $carDB->limit($pageSize, $page*$pageSize)->get()->result_array()
        );

        return success($data);
    }

    public function get_car_count() {
        return $this->db->from(TABLE_CAR)->count_all_results();
    }
}