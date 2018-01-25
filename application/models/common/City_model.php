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
                return fail_result('无效的 provinceId : '.$province_id);
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
                array_push($data, $city);
            } else {
                return $city;
            }
        }

        $city_list = array('list' => $data);
        
        return success_result('查询成功', $city_list);
    }

    private function get_city_by_province_id($province_id) {
        $province_info = $this->db->where('id', $province_id)
                        ->from(self::TABLE_NAME_PROVINCE)
                        ->select('id, name')
                        ->get()
                        ->row_array();
        if(empty($province_info)) {
            return fail_result('无效的 provinceId : '.$province_id);
        }
        $city_info = $this->db->where('province_id', $province_id)
                        ->from(self::TABLE_NAME_CITY)
                        ->select('id as cityId, name as cityName, first_char as firstChar')
                        ->get()
                        ->result_array();
        $data = array(
            'provinceId' => $province_id,
            'provinceName' => $province_info['name'],
            'list' => $city_info
        );

        return $data;
    }

    public function get_city_sort() {
        $data = array();
        for($i = ord("A"); $i <= ord("Z"); $i++){
            $letter = chr($i);
            $city_info = $this->db->where('first_char', $letter)
                                ->from(self::TABLE_NAME_CITY)
                                ->select('id as cityId, name as cityName, first_char as firstChar')
                                ->get()
                                ->result_array();
            if(!empty($city_info)) {
                $data[$letter] = $city_info;
            }
        }

        return success_result('查询成功', array('list'=>$data));
    }
}
