<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Common_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_banner() {
        $list =  $this->db->from(TABLE_BANNER)
                            ->select('banner, link')
                            ->order_by('position', 'ASC')
                            ->order_by('time', 'DESC')
                            ->where('status', 1)
                            ->get()
                            ->result_array();

        return $list;
    }

    public function add_banner($banner, $url, $position, $title) {
        $data = array(
            'time'=> time(),
            'banner'=> $banner,
            'link'=> $url,
            'position'=> $position,
            'title'=> $title
        );
        $this->db->insert(TABLE_BANNER, $data);

        return '添加完成';
    }

    public function edit_banner($id, $bannerImg, $url, $position, $title) {
        $banner = $this->db->from(TABLE_BANNER)
                            ->where('id', $id)
                            ->get()
                            ->row_array();
        if(empty($banner)) {
            return fail('没有该banner');
        }
        if($banner['status'] == '1') {
            return fail('下架的banner不能再修改');
        }

        $data = array(
            'banner'=> $bannerImg,
            'link'=> $url,
            'position'=> $position,
            'title'=> $title
        );
        $this->db->where('id', $id)->update(TABLE_BANNER, $data);

        return success('修改完成');
    }

    public function under_banner($id) {
        $banner = $this->db->from(TABLE_BANNER)
                            ->where('id', $id)
                            ->get()
                            ->row_array();
        if(empty($banner)) {
            return fail('没有该banner');
        }
        if($banner['status'] == '1') {
            return fail('该banner已经是下架状态');
        }

        $data['status'] = '1';
        $data['under_time'] = time();

        $this->db->where('id', $id)->update(TABLE_BANNER, $data);

        return success('下架成功');
    }

    public function get_banner_list() {
        $list =  $this->db->from(TABLE_BANNER)
                            ->select('id, banner, time, link, position, status, under_time as underTime, title')
                            ->order_by('status', 'ASC')
                            ->order_by('position', 'DESC')
                            ->order_by('time', 'DESC')
                            ->get()
                            ->result_array();

        return $list;
    }
}