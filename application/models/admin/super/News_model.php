<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function publish($user_id, $opt = array())
    {
        $keys = array(
            'title' => '请输入标题',
            'info' => '请输入资讯简介',
            'imageUrl' => '请上传封面图',
            'content' => '资讯内容不能为空',
            'from' => '请输入文章来源'
        );
        $k = array(
            'title',
            'info',
            'imageUrl',
            'content',
            'from'
        );
        $opt = elements($k, $opt, '');
        foreach($keys as $k => $v){
            if('' == $opt[$k]) {
                return fail($keys[$k]);
            }
        }

        $news_id = create_car_id($user_id);

        $data = array(
            'news_id' => $news_id,
            'user_id' => $user_id,
            'news_title' => $opt['title'],
            'news_info' => $opt['info'],
            'news_img' => $opt['imageUrl'],
            'news_content' => $opt['content'],
            'news_time' => time(),
            'from' => $opt['from']
        );
        // 添加数据
        $suc = $this->db->insert(TABLE_NEWS, $data);

        if (!$suc) {
            return fail('提交失败');
        }
        
        return success(array('newsId'=> $news_id));
    }

    public function get_news_list($page, $pageSize) {

        $newsList = $this->db->from(TABLE_NEWS)
                                ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, 
                                            news_img as newsImg, news_info as newsInfo, see_count as seeCount, from')
                                ->limit($pageSize, $page*$pageSize)
                                ->order_by('news_time', 'DESC')
                                ->get()
                                ->result_array();
        
        return success($newsList);
    }

    public function get_news_count() {
        $countAll = $this->db->from(TABLE_NEWS)->count_all_results();
        return success($countAll);
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
        
        return success($news);
    }

    public function delete($news_id) {
        $isEx = $this->db->where('news_id', $news_id)->count_all_results(TABLE_NEWS);
        if ($isEx == 0) {
            return fail('文章不存在');
        }
        $this->db->where('news_id', $news_id)->delete(TABLE_NEWS);
        return success('已删除');
    }
}
