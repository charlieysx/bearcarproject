<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class News extends Base_Controller
{
    public function __construct() {
        parent::__construct();

        $this->load->model('user/News_model', 'news');
    }

    public function get_news_list() {
        $params = $this->input->get();
        $pageOpt = get_page($params);
        $newsList = $this->news->get_news_list($pageOpt['page'], $pageOpt['pageSize']);
        $newsCount = $this->news->get_news_count();
        $result = array(
            'page'=> $pageOpt['page'],
            'pageSize'=> $pageOpt['pageSize'],
            'count'=> $newsCount['msg'],
            'list'=> $newsList['msg']
        );

        $this->return_success($result);
    }

    public function get_hot_news_list() {
        $params = $this->input->get();
        $count = get_param($params, 'count', 8);
        if(!is_p_number($count)) {
            $count = 8;
        }
        $hotNewsList = $this->news->get_hot_news_list($count);

        $this->return_result($hotNewsList);
    }

    public function get_news_info() {
        $params = $this->input->get();
        $newsId = get_param($params, 'newsId');
        $result = $this->news->get_news_info($newsId);

        $this->return_result($result);
    }
}
