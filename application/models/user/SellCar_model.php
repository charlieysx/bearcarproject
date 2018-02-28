<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SellCar_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sell_car($user_id, $opt = array()) {
        $keys = array(
          'brandId' => '请选择车品牌',
          'seriesId' => '请选择车系',
          'modelId' => '请选择车型',
          'year' => '请选择上牌时间',
          'month' => '请选择上牌时间',
          'driverMileage' => '请填写行驶里程',
          'guohu' => '请选择过户次数',
          'licenseCity' => '请选择牌照地',
          'condition' => '请选择车况',
          'expireDate' => '请选择预期售出时间',
          'checkTime' => '请选择验车时间',
          'provinceId' => '请选择省份',
          'cityId' => '请选择城市',
          'address' => '请填写详细地址'
        );
        $key = array(
          'brandId',
          'seriesId',
          'modelId',
          'year',
          'month',
          'driverMileage',
          'guohu',
          'licenseCity',
          'condition',
          'expireDate',
          'checkTime',
          'provinceId',
          'cityId',
          'districtId',
          'address'
        );
        $opt = elements($key, $opt, '');
        foreach($keys as $k => $v){
            if('' == $opt[$k]) {
              return fail($keys[$k]);
            }
        }

        $data = array(
          'car_id' => create_car_id($user_id),
          'user_id' => $user_id,
          'brand_id' => $opt['brandId'],
          'series_id' => $opt['seriesId'],
          'model_id' => $opt['modelId'],
          'licensed_year' => $opt['year'],
          'licensed_month' => $opt['month'],
          'mileage' => $opt['driverMileage'],
          'transfer_time' => $opt['guohu'],
          'licensed_city_id' => $opt['licenseCity'],
          'car_condition_id' => $opt['condition'],
          'expire_date_id' => $opt['expireDate'],
          'inspect_address' => $opt['address'],
          'inspect_datetime' => $opt['checkTime'],
          'province_id' => $opt['provinceId'],
          'city_id' => $opt['cityId'],
          'district_id' => $opt['districtId'],
          'publish_time' => time()
      );

      // 添加数据
      $suc = $this->db->insert(TABLE_CAR, $data);

      if (!$suc) {
          return fail('提交失败');
      }
      return success('提交成功');
    }


    public function get_sell_info() {
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
        //车况
        $car_condition = $this->db->from(TABLE_CAR_CONDITION)
                            ->select('condition_id as conditionId, condition_name as value')
                            ->get()
                            ->result_array();
        //预期出售时间
        $expire_date = $this->db->from(TABLE_EXPIRE_DATE)
                            ->select('expire_date_id as expireDateId, expire_date_name as value')
                            ->get()
                            ->result_array();

        $data = array(
            'licenseTime' => $license_time,
            'condition' => $car_condition,
            'expireDate' => $expire_date
        );

        return $data;
    }

    public function get_check_time() {
        //验车时间
        $check_time = $this->db->from(TABLE_CHECK_TIME)
                            ->select('check_time_id as id, value')
                            ->get()
                            ->result_array();

        $check_time[0]['disable'] = true;
        $check_time[1]['disable'] = true;
        $check_time[2]['disable'] = true;
        $check_time[3]['disable'] = true;
        $check_time[4]['disable'] = true;

        $hour = intval(date("H"));
        $minute = intval(date("i"));
        if($hour > 11 || ($hour == 11 && $minute > 30)) {
            $check_time[0]['disable'] = false;
        };
        if($hour > 17 || ($hour == 17 && $minute > 30)) {
            $check_time[1]['disable'] = false;
        }
        
        return $check_time;
    }
}
