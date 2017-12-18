<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Model.php';

class CarInfo_model extends Base_Model
{
    //车品牌
    const TABLE_NAME_CAR_BRAND = 'car_brand';
    //车系列
    const TABLE_NAME_CAR_SERIES = 'car_series';
    //车型
    const TABLE_NAME_CAR_MODEL = 'car_model';

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
}
