<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Table_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_user($page, $pageSize, $sort) {
        $userList = $this->db->from(TABLE_USER)
                        ->select('user_id as userId, phone, last_login_time as lastLoginTime, login_count as loginCount,
                                status, register_time as registerTime')
                        ->limit($pageSize, $page*$pageSize)
                        ->get()
                        ->result_array();

        foreach($userList as $k => $v){
            $userId = $userList[$k]['userId'];
            $sellCount = $this->db->from(TABLE_CAR)
                                    ->where('user_id', $userId)
                                    ->count_all_results();
            $orderCount = $this->db->from(TABLE_USER_ORDER)
                                    ->where('user_id', $userId)
                                    ->count_all_results();
            $buyCount = $this->db->from(TABLE_ORDER)
                                    ->where('user_id', $userId)
                                    ->count_all_results();
            $userList[$k]['sellCount'] = $sellCount;
            $userList[$k]['orderCount'] = $orderCount;
            $userList[$k]['buyCount'] = $buyCount;
        }
                        
        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $this->get_user_count(),
            'list'=> $userList
        );
        return $data;
    }

    public function get_user_count() {
        $countAll = $this->db->from(TABLE_USER)->count_all_results();
        return $countAll;
    }

    public function get_table_admin($page, $pageSize, $sort) {
        $adminList = $this->db->from(TABLE_ADMIN_USER)
                        ->select('user_id as userId, phone, last_login_time as lastLoginTime, login_count as loginCount,
                                status, register_time as registerTime, type, user_name as userName')
                        ->limit($pageSize, $page*$pageSize)
                        ->order_by('type', 'ASC')
                        ->order_by('register_time', 'DESC')
                        ->get()
                        ->result_array();

        foreach($adminList as $k => $v){
            $userId = $userList[$k]['userId'];
            $orderCount = $this->db->from(TABLE_ORDER)
                                    ->where('appraiser_id', $userId)
                                    ->count_all_results();
            $adminList[$k]['orderCount'] = $orderCount;
        }
                        
        $data = array(
            'page'=> $page,
            'pageSize'=> $pageSize,
            'count'=> $this->get_admin_count(),
            'list'=> $adminList
        );
        return $data;
    }

    public function get_admin_count() {
        $countAll = $this->db->from(TABLE_ADMIN_USER)->count_all_results();
        return $countAll;
    }
}
