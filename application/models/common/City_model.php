<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH. 'core/Base_Model.php';

class City_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_province($province_id) {
        $provinceDB = $this->db->from(TABLE_PROVINCE)
                                ->not_like('name', '特别行政区', 'before')
                                ->select('id as provinceId, name as provinceName');
        $data = array();
        if('' != $province_id) {
            $data = $provinceDB->where('id', $province_id)->get()->row_array();
            if(empty($data)) {
                return fail('无效的 provinceId : '.$province_id);
            }
        } else {
            $data = array('list'=> $provinceDB->get()->result_array());
        }
        
        return success($data);
    }

    public function get_city($city_id) {
        $cityDB = $this->db->from(TABLE_CITY)
                                ->select('id as cityId, name as cityName')
                                ->not_like('name', '特别行政区', 'before');
        $data = array();
        if('' != $city_id) {
            $data = $cityDB->where('id', $city_id)->get()->row_array();
            if(empty($data)) {
                return fail('无效的 cityId : '.$city_id);
            }
        } else {
            $data = array('list'=> $cityDB->get()->result_array());
        }
        
        return success($data);
    }

    public function get_city_by_province_id($province_id) {
        $province = $this->get_province($province_id);
        if(!$province['success']) {
            return $province;
        }

        $cityList = $this->db->from(TABLE_CITY)
                                ->where('province_id', $province_id)
                                ->not_like('name', '特别行政区', 'before')
                                ->select('id as cityId, name as cityName, first_char as firstChar')
                                ->get()
                                ->result_array();
        foreach($cityList as $k => $v){
            $pos = strpos($v['cityName'], '市');
            if(!$pos) {
                $pos = strpos($v['cityName'], '地区');
            }
            if($pos) {
                $cityList[$k]['cityName'] = substr($v['cityName'], 0, $pos);
            }
        }
        $data = array(
            'province' => $province['msg'],
            'list' => $cityList
        );

        return success($data);
    }

    public function get_district_by_city_id($city_id) {
        $city = $this->get_city($city_id);
        if(!$city['success']) {
            return $city;
        }

        $districtList = $this->db->where('city_id', $city_id)
                        ->from(TABLE_DISTRICT)
                        ->select('id as districtId, name as districtName')
                        ->get()
                        ->result_array();
        $data = array(
            'city' => $city['msg'],
            'list' => $districtList
        );
        
        return success($data);
    }

    public function get_info_by_city($city_id) {
        $city = $this->get_city($city_id);
        if(!$city['success']) {
            return $city;
        }
        
        $province = $this->db->from(TABLE_CITY)
                                ->where('id', $city_id)
                                ->not_like('name', '特别行政区', 'before')
                                ->select('province_id as provinceId, province_name as provinceName')
                                ->get()
                                ->row_array();
        $cityList = $this->get_city_by_province_id($province['provinceId'])['msg']['list'];
        foreach($cityList as $k => $v){
            $pos = strpos($v['cityName'], '市');
            if(!$pos) {
                $pos = strpos($v['cityName'], '地区');
            }
            if($pos) {
                $cityList[$k]['cityName'] = substr($v['cityName'], 0, $pos);
            }
        }
        $districtList = $this->get_district_by_city_id($city_id);
        $data = array(
            'province' => $province,
            'city'=> $city['msg'],
            'cityList' => $cityList,
            'districtList' => $districtList['msg']['list']
        );
        return success($data);
    }

    public function get_city_sort() {
        $data = array();
        for($i = ord("A"); $i <= ord("Z"); $i++){
            $letter = chr($i);
            $cityList = $this->db->where('first_char',  $letter)
                                ->not_like('name', '特别行政区', 'before')
                                ->from(TABLE_CITY)
                                ->select('id as cityId, name as cityName, first_char as firstChar')
                                ->get()
                                ->result_array();
            if(!empty($cityList)) {
                foreach($cityList as $k => $v){
                    $pos = strpos($v['cityName'], '市');
                    if(!$pos) {
                        $pos = strpos($v['cityName'], '地区');
                    }
                    if($pos) {
                        $cityList[$k]['cityName'] = substr($v['cityName'], 0, $pos);
                    }
                }
                $data[$letter] = $cityList;
            }
        }

        return $data;
    }

    public function get_hot_city($count) {
        $cityList = $this->db->order_by('search_count', 'DESC')
                            ->limit($count)
                            ->from(TABLE_CITY)
                            ->select('id as cityId, name as cityName, first_char as firstChar, search_count as searchCount')
                            ->get()
                            ->result_array();

        foreach($cityList as $k => $v){
            $pos = strpos($v['cityName'], '市');
            if(!$pos) {
                $pos = strpos($v['cityName'], '地区');
            }
            if($pos) {
                $cityList[$k]['cityName'] = substr($v['cityName'], 0, $pos);
            }
        }

        return array('list'=> $cityList);
    }
}
