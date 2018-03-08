<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Statistics_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_statistics() {
        $today = strtotime('today') + 24 *  60 * 60 - 1;

        $allCount = intval($this->db->from(TABLE_CAR)->count_all_results());
        $sellCount = intval($this->db->from(TABLE_ORDER)->where('step', '4')->count_all_results());
        $successCount = intval($this->db->from(TABLE_ORDER)->where('status', '3')->count_all_results());
        $userCount = intval($this->db->from(TABLE_USER)->count_all_results());

        $cityData = $this->db->from(TABLE_CAR)
                        ->select('city.name as cityName, lng, lat, count(*) as count')
                        ->join(TABLE_CITY, 'city.id = car.licensed_city_id')
                        ->group_by('car.licensed_city_id')
                        ->order_by('count(*)', 'DESC')
                        ->get()
                        ->result_array();

        $apply = $this->get_apply($today);
        $sell = $this->get_sell($today);
        $user = $this->get_user($today);

        return success(array(
            'allCount'=> $allCount,
            'sellCount'=> $sellCount,
            'successCount'=> $successCount,
            'userCount'=> $userCount,
            'cityData'=> $cityData,
            'apply'=> $apply,
            'sell'=> $sell,
            'user'=> $user
        ));
    }

    private function get_apply($today) {
      $data = array();
      for($i = 0;$i < 7;++$i) {
        $d = date('Y-m-d', $today);
        $lastDay = $today - DAY;
        $count = $this->db->from(TABLE_CAR)
                              ->where('publish_time <=', $today)
                              ->where('publish_time >', $lastDay)
                              ->count_all_results();
        array_push($data, array(
          $d,
          $count
        ));
        $today = $today - DAY;
      }
      return array_reverse($data);
    }

    private function get_sell($today) {
      $data = array();
      for($i = 0;$i < 7;++$i) {
        $d = date('Y-m-d', $today);
        $lastDay = $today - DAY;
        $count = $this->db->from(TABLE_CAR)
                              ->where('publish_time <=', $today)
                              ->where('publish_time >', $lastDay)
                              ->join(TABLE_ORDER, 'order.car_id = car.car_id')
                              ->where('order.step', '4')
                              ->count_all_results();
        array_push($data, array(
          $d,
          $count
        ));
        $today = $today - DAY;
      }
      return array_reverse($data);
    }

    private function get_user($today) {
      $data = array(
        array(),
        array(),
        array()
      );
      for($i = 0;$i < 7;++$i) {
        $d = date('Y-m-d', $today);
        $lastDay = $today - DAY;
        $count = $this->db->from(TABLE_USER)
                              ->where('register_time <=', $today)
                              ->where('register_time >', $lastDay)
                              ->count_all_results();
        $activeCount = $this->db->from(TABLE_USER)
                              ->where('last_login_time <=', $today)
                              ->where('last_login_time >', $lastDay)
                              ->count_all_results();
        array_push($data[0], array(
          $d
        ));
        array_push($data[1], array(
          $count
        ));
        array_push($data[2], array(
          $activeCount
        ));
        $today = $today - DAY;
      }

      array_push($data[0], array(
        'product'
      ));
      array_push($data[1], array(
        '新增用户'
      ));
      array_push($data[2], array(
        '活跃用户'
      ));

      $data[0] = array_reverse($data[0]);
      $data[1] = array_reverse($data[1]);
      $data[2] = array_reverse($data[2]);

      return $data;
    }
}