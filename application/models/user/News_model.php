<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_news_list($page, $pageSize) {
        $news_list = $this->db->from(TABLE_NEWS)
                                ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, 
                                            news_img as newsImg, news_info as newsInfo, see_count as seeCount, from')
                                ->limit($pageSize, $page*$pageSize)
                                ->order_by('news_time', 'DESC')
                                ->get()
                                ->result_array();
        
        return success($news_list);
    }

    public function get_news_count() {
        $count_all = $this->db->from(TABLE_NEWS)->count_all_results();
        return success($count_all);
    }

    public function get_hot_news_list($count) {
        $news_list = $this->db->from(TABLE_NEWS)
                                ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, 
                                            news_img as newsImg, news_info as newsInfo, see_count as seeCount, from')
                                ->limit($count)
                                ->order_by('see_count', 'DESC')
                                ->get()
                                ->result_array();
        
        return success($news_list);
    }

    public function get_news_info($news_id) {
        $isEx = $this->db->where('news_id', $news_id)->count_all_results(TABLE_NEWS);
        if ($isEx == 0) {
            return fail('文章不存在');
        }
        $news = $this->db->from(TABLE_NEWS)
                            ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, see_count as seeCount, from, news_content as newsContent')
                            ->where('news_id', $news_id)
                            ->get()
                            ->row_array();
        
        $data = array(
            'see_count' => intval($news['seeCount']) + 1
        );

        // 更新数据
        $this->db->where('news_id', $news_id)->update(TABLE_NEWS, $data);

        $news['seeCount'] = intval($news['seeCount']) + 1;
        
        return success($news);
    }
}
