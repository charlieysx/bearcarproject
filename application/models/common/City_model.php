<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH. 'core/Base_Model.php';

class City_model extends Base_Model
{
    //省份
    const TABLE_NAME_PROVINCE = 'province';
    //城市
    const TABLE_NAME_CITY = 'city';
    //地区
    const TABLE_NAME_DISTRICT = 'district';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_province($province_id) {
        $data = array();
        if('' == $province_id) {
            $data = $this->db->from(self::TABLE_NAME_PROVINCE)
                            ->not_like('name', '特别行政区', 'before')
                            ->select('id as provinceId, name as provinceName')
                            ->get()
                            ->result_array();
        } else {
            $data = $this->db->where('id', $province_id)
                            ->from(self::TABLE_NAME_PROVINCE)
                            ->select('id as provinceId, name as provinceName')
                            ->get()
                            ->result_array();
            if(empty($data)) {
                return success_result('无效的 provinceId : '.$province_id);
            }
        }

        $province_list = array('list' => $data);
        
        return success_result('查询成功', $province_list);
    }

    public function get_city($province_id) {
        $data = array();
        if('' == $province_id) {
            $province_info = $this->db->from(self::TABLE_NAME_PROVINCE)
                            ->select('id, name')
                            ->get()
                            ->result_array();
            
            foreach($province_info as $province) {
                $list = $this->get_city_by_province_id($province['id']);
                array_push($data, $list);
            }
        } else {
            $city = $this->get_city_by_province_id($province_id);
            if(!isset($city['status'])) {
                return success_result('查询成功', $city);
            } else {
                return $city;
            }
        }

        $city_list = array('list' => $data);
        
        return success_result('查询成功', $city_list);
    }

    public function get_city_by_province_id($province_id) {
        $province_info = $this->db->where('id', $province_id)
                        ->not_like('name', '自治州', 'before')
                        ->not_like('name', '特别行政区', 'before')
                        ->from(self::TABLE_NAME_PROVINCE)
                        ->select('id, name')
                        ->get()
                        ->row_array();
        if(empty($province_info)) {
            return success_result('无效的 provinceId : '.$province_id);
        }
        $city_info = $this->db->where('province_id', $province_id)
                        ->from(self::TABLE_NAME_CITY)
                        ->select('id as cityId, name as cityName, first_char as firstChar')
                        ->get()
                        ->result_array();
        foreach($city_info as $k => $v){
            $pos = strpos($v['cityName'], '市');
            if(!$pos) {
                $pos = strpos($v['cityName'], '地区');
            }
            if($pos) {
                $city_info[$k]['cityName'] = substr($v['cityName'], 0, $pos);
            }
        }
        $data = array(
            'provinceId' => $province_id,
            'provinceName' => $province_info['name'],
            'list' => $city_info
        );

        return $data;
    }

    public function get_district_by_city_id($city_id) {
        $city_info = $this->db->where('id', $city_id)
                        ->from(self::TABLE_NAME_CITY)
                        ->select('id, name')
                        ->get()
                        ->row_array();
        if(empty($city_info)) {
            return success_result('无效的 cityId : '.$city_id);
        }
        $district_info = $this->db->where('city_id', $city_id)
                        ->from(self::TABLE_NAME_DISTRICT)
                        ->select('id as districtId, name as districtName')
                        ->get()
                        ->result_array();
        $data = array(
            'cityId' => $city_id,
            'cityName' => $city_info['name'],
            'list' => $district_info
        );
        
        return success_result('查询成功', $data);
    }

    public function get_info_by_city($city_id) {
        $city_info = $this->db->where('id', $city_id)
                        ->from(self::TABLE_NAME_CITY)
                        ->select('id, name')
                        ->get()
                        ->row_array();
        if(empty($city_info)) {
            return success_result('无效的 cityId : '.$city_id);
        }
        $province_info = $this->db->where('id', $city_id)
                        ->from(self::TABLE_NAME_CITY)
                        ->not_like('name', '特别行政区', 'before')
                        ->select('province_id as provinceId, province_name as provinceName')
                        ->get()
                        ->row_array();
        $city_list = $this->db->where('province_id', $province_info['provinceId'])
                        ->from(self::TABLE_NAME_CITY)
                        ->select('id as cityId, name as cityName, first_char as firstChar')
                        ->get()
                        ->result_array();
        foreach($city_list as $k => $v){
            $pos = strpos($v['cityName'], '市');
            if(!$pos) {
                $pos = strpos($v['cityName'], '地区');
            }
            if($pos) {
                $city_list[$k]['cityName'] = substr($v['cityName'], 0, $pos);
            }
        }
        $district_list = $this->db->where('city_id', $city_id)
                        ->from(self::TABLE_NAME_DISTRICT)
                        ->select('id as districtId, name as districtName')
                        ->get()
                        ->result_array();
        $data = array(
            'province' => $province_info,
            'cityList' => $city_list,
            'districtList' => $district_list
        );
        return success_result('查询成功', $data);
    }

    public function get_city_sort() {
        $data = array();
        for($i = ord("A"); $i <= ord("Z"); $i++){
            $letter = chr($i);
            $city_info = $this->db->where('first_char',  $letter)
                                ->not_like('name', '自治州', 'before')
                                ->not_like('name', '特别行政区', 'before')
                                ->from(self::TABLE_NAME_CITY)
                                ->select('id as cityId, name as cityName, first_char as firstChar')
                                ->get()
                                ->result_array();
            if(!empty($city_info)) {
                foreach($city_info as $k => $v){
                    $pos = strpos($v['cityName'], '市');
                    if(!$pos) {
                        $pos = strpos($v['cityName'], '地区');
                    }
                    if($pos) {
                        $city_info[$k]['cityName'] = substr($v['cityName'], 0, $pos);
                    }
                }
                $data[$letter] = $city_info;
            }
        }

        return success_result('查询成功', array('list'=>$data));
    }

    public function get_hot_city() {
        $city_info = $this->db->order_by('search_count', 'DESC')
                            ->limit(11)
                            ->from(self::TABLE_NAME_CITY)
                            ->select('id as cityId, name as cityName, first_char as firstChar, search_count as searchCount')
                            ->get()
                            ->result_array();
        foreach($city_info as $k => $v){
            $pos = strpos($v['cityName'], '市');
            if(!$pos) {
                $pos = strpos($v['cityName'], '地区');
            }
            if($pos) {
                $city_info[$k]['cityName'] = substr($v['cityName'], 0, $pos);
            }
        }

        return success_result('查询成功', array('list'=>$city_info));
    }
}
