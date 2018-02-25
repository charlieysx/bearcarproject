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
          'content' => '资讯内容不能为空'
        );
        $k = array(
          'title',
          'info',
          'imageUrl',
          'content'
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
            'news_time' => time()
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
                                        news_img as newImg, news_info as newsInfo, see_count as seeCount,
                                        news_content as newsContent')
                              ->limit($pageSize, $page*$pageSize)
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
}
