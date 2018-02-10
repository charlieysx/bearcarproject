<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH. 'core/Base_Model.php';

class CarInfo_model extends Base_Model
{
    //车品牌
    const TABLE_NAME_CAR_BRAND = 'car_brand';
    //车系列
    const TABLE_NAME_CAR_SERIES = 'car_series';
    //车型
    const TABLE_NAME_CAR_MODEL = 'car_model';
    //车况
    const TABLE_NAME_CONDITION = 'car_condition';
    //预期出售时间
    const TABLE_NAME_EXPIRE_DATE = 'expire_date';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_brand($brand_id) {
        $data = array();
        if('' == $brand_id) {
            $data = $this->db->from(self::TABLE_NAME_CAR_BRAND)
                            ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar')
                            ->get()
                            ->result_array();
        } else {
            $data = $this->db->where('brand_id', $brand_id)
                            ->from(self::TABLE_NAME_CAR_BRAND)
                            ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar')
                            ->get()
                            ->result_array();
            if(empty($data)) {
                return fail_result('无效的 brandId : '.$brand_id);
            }
        }

        $province_list = array('list' => $data);
        
        return success_result('查询成功', $province_list);
    }
        
    public function get_series_by_brand_id($brand_id) {
        $data = array();
        if('' == $brand_id) {
            return fail_result('brandId 不能为空');
        } else {
            $data = $this->db->where('brand_id', $brand_id)
                            ->from(self::TABLE_NAME_CAR_SERIES)
                            ->select('brand_id as brandId, series_id as seriesId, series_name as seriesName')
                            ->get()
                            ->result_array();
            if(empty($data)) {
                return fail_result('无效的 brandId : '.$brand_id);
            }
        }
        
        $province_list = array('list' => $data);
                
        return success_result('查询成功', $province_list);
    }
    
    public function get_model_by_series_id($series_id) {
        $data = array();
        if('' == $series_id) {
            return fail_result('seriesId 不能为空');
        } else {
            $data = $this->db->where('series_id', $series_id)
                            ->from(self::TABLE_NAME_CAR_MODEL)
                            ->select('model_id as modelId, model_name as modelName, series_id as seriesId')
                            ->get()
                            ->result_array();
            if(empty($data)) {
                return fail_result('无效的 seriesId : '.$series_id);
            }
        }
    
        $province_list = array('list' => $data);
            
        return success_result('查询成功', $province_list);
    }

    public function get_brand_sort() {
        $data = array();
        for($i = ord("A"); $i <= ord("Z"); $i++){
            $letter = chr($i);
            $car_info = $this->db->where('first_char',  $letter)
                                ->from(self::TABLE_NAME_CAR_BRAND)
                                ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar')
                                ->get()
                                ->result_array();
            if(!empty($car_info)) {
                $data[$letter] = $car_info;
            }
        }

        return success_result('查询成功', array('list'=>$data));
    }

    public function get_hot_brand($count) {
        $car_info = $this->db->order_by('search_count', 'DESC')
                            ->limit($count)
                            ->from(self::TABLE_NAME_CAR_BRAND)
                            ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar')
                            ->get()
                            ->result_array();

        return success_result('查询成功', array('list'=>$car_info));
    }

    public function get_hot_series($count) {
        $car_info = $this->db->order_by('search_count', 'DESC')
                            ->limit($count)
                            ->from(self::TABLE_NAME_CAR_SERIES)
                            ->select('brand_id as brandId, series_id as seriesId, series_name as seriesName')
                            ->get()
                            ->result_array();

        return success_result('查询成功', array('list'=>$car_info));
    }

    public function get_sell_info() {
        //上牌时间
        $year = intval(date( "Y"));
        $month = intval(date( "n"));
        $license_time = array();
        for($i = 0;$i < 5;$i++) {
            
            $m = array();
            for($j = 1;$j <= ($i == 0 ? $month : 12);$j++) {
                array_push($m, array('value' => $j.'月' ));
            }
            $item = array(
                'value' => ($year + $i).'年',
                'month' => $m
            );
            array_push($license_time, $item);
        }
        //车况
        $car_condition = $this->db->from(self::TABLE_NAME_CONDITION)
                            ->select('condition_id as conditionId, condition_name as value')
                            ->get()
                            ->result_array();
        //预期出售时间
        $expire_date = $this->db->from(self::TABLE_NAME_EXPIRE_DATE)
                            ->select('expire_date_id as expireDateId, expire_date_name as value')
                            ->get()
                            ->result_array();

        $data = array(
            'licenseTime' => $license_time,
            'condition' => $car_condition,
            'expireDate' => $expire_date
        );

        return success_result('查询成功', $data);
    }

    public function get_check_time() {
        $data = array(
            array(
                'id' => 0,
                'value' => '今天上午9:00-12:00',
                'disable' => true
            ),
            array(
                'id' => 1,
                'value' => '今天下午12:00-18:00',
                'disable' => true
            ),
            array(
                'id' => 2,
                'value' => '明天上午9:00-12:00',
                'disable' => true
            ),
            array(
                'id' => 3,
                'value' => '明天下午12:00-18:00',
                'disable' => true
            ),
            array(
                'id' => 4,
                'value' => '以上时间均不方便，请客服联系我',
                'disable' => true
            )
        );
        $hour = intval(date("H"));
        if($hour > 11) {
            $data[0]['disable'] = false;
        };
        if($hour > 17) {
            $data[1]['disable'] = false;
        }
        
        return success_result('查询成功', $hour);
    }
}
