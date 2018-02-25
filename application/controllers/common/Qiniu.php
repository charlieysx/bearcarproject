<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Qiniu extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('common/Qiniu_model', 'qiniu');
    }

    public function get_qiniu_token() {
        $this->check_token();
        $param = $this->input->get();
        $bucket = '';
        if(isset($param['bucket'])) {
            $bucket = $param['bucket'];
        }
        $result = $this->qiniu->get_upload_token($bucket);

        $this->return_result($result);
    }
}
