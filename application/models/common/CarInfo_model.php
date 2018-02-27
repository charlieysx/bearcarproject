<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CarInfo_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_brand($brand_id = '') 
    {
        $brandDB = $this->db->from(TABLE_CAR_BRAND)
                            ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar');
        $data = array();
        if('' != $brand_id) {
            $data = $brandDB->where('brand_id', $brand_id)->get()->row_array();
            if(empty($data)) {
                return fail('无效的 brandId : '.$brand_id);
            }
        } else {
            $data = array('list'=> $brandDB->get()->result_array());
        }
        
        return success($data);
    }
        
    public function get_series_by_brand_id($brand_id) 
    {
        $brand = $this->get_brand($brand_id);
        if(!$brand['success']) {
            return $brand;
        }
        $data = $this->db->where('brand_id', $brand_id)
                            ->from(TABLE_CAR_SERIES)
                            ->select('series_id as seriesId, series_name as seriesName')
                            ->get()
                            ->result_array();
        return success(
            array(
                'brand'=> $brand['msg'],
                'list'=> $data
            )
        );
    }

    private function get_series($series_id = '') 
    {
        $seriesDB = $this->db->from(TABLE_CAR_SERIES)
                            ->select('series_id as seriesId, series_name as seriesName');
        $data = array();
        if('' != $series_id) {
            $data = $seriesDB->where('series_id', $series_id)->get()->row_array();
            if(empty($data)) {
                return fail('无效的 seriesId : '.$series_id);
            }
        } else {
            $data = array('list'=> $seriesDB->get()->result_array());
        }
        
        return success($data);
    }
    
    public function get_model_by_series_id($series_id) 
    {
        $series = $this->get_series($series_id);
        if(!$series['success']) {
            return $series;
        }
        $data = $this->db->where('series_id', $series_id)
                            ->from(TABLE_CAR_MODEL)
                            ->select('model_id as modelId, model_name as modelName')
                            ->get()
                            ->result_array();
        return success(
            array(
                'series'=> $series['msg'],
                'list'=> $data
            )
        );
    }

    public function get_brand_sort() {
        $data = array();
        for($i = ord("A"); $i <= ord("Z"); $i++){
            $letter = chr($i);
            $brandList = $this->db->where('first_char',  $letter)
                                ->from(TABLE_CAR_BRAND)
                                ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar')
                                ->get()
                                ->result_array();
            if(!empty($car_info)) {
                $data[$letter] = $brandList;
            }
        }

        return $data;
    }

    public function get_hot_brand($count) {
        $brandList = $this->db->order_by('search_count', 'DESC')
                            ->limit($count)
                            ->from(TABLE_CAR_BRAND)
                            ->select('brand_id as brandId, brand_name as brandName, first_char as firstChar')
                            ->get()
                            ->result_array();

        return array('list'=> $brandList);
    }

    public function get_hot_series($count) {
        $seriesList = $this->db->order_by('search_count', 'DESC')
                            ->limit($count)
                            ->from(TABLE_CAR_SERIES)
                            ->select('brand_id as brandId, series_id as seriesId, series_name as seriesName')
                            ->get()
                            ->result_array();

        return array('list'=> $seriesList);
    }
}
