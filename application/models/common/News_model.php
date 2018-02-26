<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News_model extends Base_Model
{
    const TABLE_NAME = 'news';

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
              return fail_result($keys[$k]);
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
        $suc = $this->db->insert(self::TABLE_NAME, $data);

        if (!$suc) {
            return fail_result('提交失败');
        }
        
        return success_result('发布完成', array('newsId'=> $news_id));
    }

    public function get_news_list($page = 0, $pageSize = 15) {
        $news_list = $this->db->from(self::TABLE_NAME)
                              ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, 
                                        news_img as newsImg, news_info as newsInfo, see_count as seeCount, from')
                              ->limit($pageSize, $page*$pageSize)
                              ->order_by('news_time', 'DESC')
                              ->get()
                              ->result_array();
        $count_all = $this->db->from(self::TABLE_NAME)->count_all_results();
        
        $result = array(
          'page'=> $page,
          'pageSize'=> $pageSize,
          'sizeAll'=> $count_all,
          'list'=> $news_list
        );
        return success_result('查询成功', $result);
    }

    public function get_hot_news_list() {
        $news_list = $this->db->from(self::TABLE_NAME)
                              ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, 
                                        news_img as newsImg, news_info as newsInfo, see_count as seeCount, from')
                              ->limit(8)
                              ->order_by('see_count', 'DESC')
                              ->get()
                              ->result_array();
        
        $result = array(
          'list'=> $news_list
        );
        return success_result('查询成功', $result);
    }

    public function get_news_info($news_id, $is_admin = 'false') {
        $isEx = $this->db->where('news_id', $news_id)->count_all_results(self::TABLE_NAME);
        if ($isEx == 0) {
            return fail_result('文章不存在');
        }
        $news = $this->db->from(self::TABLE_NAME)
                        ->select('news_id as newsId, news_title as newsTitle, news_time as newsTime, see_count as seeCount, from, news_content as newsContent')
                        ->where('news_id', $news_id)
                        ->get()
                        ->row_array();
        
        if (!($is_admin == 'true')) {
            $data = array(
                'see_count' => intval($news['seeCount']) + 1
            );

            // 更新数据
            $this->db->where('news_id', $news_id)->update(self::TABLE_NAME, $data);

            $news['seeCount'] = intval($news['seeCount']) + 1;
        }

        // $news['newsContent'] = str_replace("src=\"", "src=\"http://bearcarapi.codebear.cn/index.php/img?imageUrl=", $news['newsContent']);
        
        return success_result('查询成功', $news);
    }

    public function delete($news_id) {
        $isEx = $this->db->where('news_id', $news_id)->count_all_results(self::TABLE_NAME);
        if ($isEx == 0) {
            return fail_result('文章不存在');
        }
        $this->db->where('news_id', $news_id)->delete(self::TABLE_NAME);
        return success_result('已删除');
    }
}
