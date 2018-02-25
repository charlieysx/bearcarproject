<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class News extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('common/News_model', 'news');
    }

    public function publish() {
        $this->isAdmin = 'true';
        $this->check_token();
        $param = $this->input->post();
        $result = $this->news->publish($this->token->userInfo['user_id'], $param);
        $this->return_result($result);
    }

    public function get_news_list() {
        $param = $this->input->get();
        $k = array(
          'page',
          'pageSize'
        );
        $opt = elements($k, $param, '');
        $opt['page'] = intval($opt['page']);
        $opt['pageSize'] = intval($opt['pageSize']);
        if($opt['page'] < 0) {
          $opt['page'] = 0;
        }
        if($opt['pageSize'] > 99 || $opt['pageSize'] <= 0) {
          $opt['pageSize'] = 15;
        }
        $result = $this->news->get_news_list($opt['page'], $opt['pageSize']);
        $this->return_result($result);
    }
}
