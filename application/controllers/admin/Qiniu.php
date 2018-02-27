<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Qiniu extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->isAdmin = true;
        $this->check_token();
        $this->load->model('common/Qiniu_model', 'qiniu');
    }

    public function get_qiniu_token() {
        $params = $this->input->get();
        $bucket = get_param($params, 'bucket', 'bearcar');
        $result = $this->qiniu->get_upload_token($bucket, $this->accessToken);

        $this->return_result($result);
    }
}
