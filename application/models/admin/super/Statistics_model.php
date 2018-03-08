<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Statistics_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_statistics() {
        $thisTime = time();

        $allCount = intval($this->db->from(TABLE_CAR)->count_all_results());
        $sellCount = intval($this->db->from(TABLE_ORDER)->where('step', '4')->count_all_results());
        $successCount = intval($this->db->from(TABLE_ORDER)->where('status', '3')->count_all_results());
        $userCount = intval($this->db->from(TABLE_USER)->count_all_results());

        return success(array(
            'allCount'=> $allCount,
            'sellCount'=> $sellCount,
            'successCount'=> $successCount,
            'userCount'=> $userCount
        ));
    }
}