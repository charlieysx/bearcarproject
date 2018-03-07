<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Common_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_banner() {
        $banner =  $this->db->from(TABLE_BANNER)
                        ->select('banner')
                        ->order_by('time', 'DESC')
                        ->get()
                        ->row_array();

        return json_decode($banner['banner']);
    }

    public function add_banner($banner) {
        $data = array(
            'time'=> time(),
            'banner'=> json_encode($banner)
        );
        $this->db->insert(TABLE_BANNER, $data);

        return '添加完成';
    }

    public function get_banner_list($page, $pageSize) {
        $list =  $this->db->from(TABLE_BANNER)
                            ->select('banner')
                            ->order_by('time', 'DESC')
                            ->limit($pageSize, $page*$pageSize)
                            ->get()
                            ->result_array();

        foreach($list as $k => $v) {
            $list[$k] = json_decode($v['banner']);
        }
        return $list;
    }
}