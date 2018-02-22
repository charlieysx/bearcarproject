<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Model.php';

class CarInfo_model extends Base_Model
{

    const TABLE_NAME = 'car';

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
        $k = array(
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
        $opt = elements($k, $opt, '');
        foreach($keys as $k => $v){
            if('' == $opt[$k]) {
              return fail_result($keys[$k]);
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
      $suc = $this->db->insert(self::TABLE_NAME, $data);

      if (!$suc) {
          return fail_result('提交失败');
      }
      return success_result('提交成功');
    }
}
