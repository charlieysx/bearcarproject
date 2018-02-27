<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class News extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        if($this->super) {
            $this->load->model('admin/super/News_model', 'news');
        } else {
            $this->load->model('admin/News_model', 'news');
        }
    }

    public function publish() {
        $param = $this->input->post();
        $result = $this->news->publish($this->token->userInfo['user_id'], $param);
        $this->return_result($result);
    }

    public function get_news_list() {
        if($this->super) {
            $this->super_get_news_list();
        } else {
            $params = $this->input->get();
            $pageOpt = get_page($params);
            $newsList = $this->news->get_news_list($this->token->userInfo['user_id'], $pageOpt['page'], $pageOpt['pageSize']);
            $newsCount = $this->news->get_news_count($this->token->userInfo['user_id']);
            $result = array(
                'page'=> $pageOpt['page'],
                'pageSize'=> $pageOpt['pageSize'],
                'count'=> $newsCount['msg'],
                'list'=> $newsList['msg']
            );

            $this->return_success($result);
        }
    }

    public function super_get_news_list() {
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

    public function get_news_info() {
        $params = $this->input->get();
        $newsId = get_param($params, 'newsId', '');
        if($newsId == '') {
            $this->return_fail('文章不存在');
        }
        $result = $this->news->get_news_info($newsId);

        $this->return_result($result);
    }

    public function delete() {
        if($this->super) {
            $this->super_delete();
        } else {
            $params = $this->input->post();
            $newsId = get_param($params, 'newsId', '');
            if($newsId == '') {
                $this->return_fail('文章不存在');
            }
            $result = $this->news->delete($this->token->userInfo['user_id'], $newsId);
            $this->return_result($result);
        }
    }

    public function super_delete() {
        $params = $this->input->post();
        $newsId = get_param($params, 'newsId', '');
        if($newsId == '') {
            $this->return_fail('文章不存在');
        }
        $result = $this->news->delete($newsId);
        $this->return_result($result);
    }
}
