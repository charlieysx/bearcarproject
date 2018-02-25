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
}
