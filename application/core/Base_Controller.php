<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    protected $accessToken = '';
    protected $isAdmin = false;
    protected $debug = true;

    public function __construct() {
        parent::__construct();

        // $isDebug = $this->input->get_request_header('isDebug', false);
        // if(!$isDebug || $isDebug != 'true') {
        //     $this->debug = false;
        //     $this->fail_response(fail_result("Error", null, -1000003), FAIL);
        // }
        $this->accessToken = $this->input->get_request_header('accessToken', '');
        $this->isAdmin = $this->input->get_request_header('isAdmin', false);

        $this->load->model('common/Token_model', 'token');
    }

    protected function success_response($data = array(), $status_code = SUCCESS)
    {
        if($data == null) {
            $data = success_result();
        }

        $this->output->set_status_header($status_code)
                    ->set_header('Content-Type: application/json; charset=utf-8')
                    ->set_output(json_encode($data));
    }

    protected function fail_response($data, $status_code = FAIL)
    {
        if($data == null) {
            $data = fail_result();
        }

        $this->output->set_status_header($status_code)
                    ->set_header("Cache-Control: no-store")
                    ->set_header('Content-Type: application/json; charset=utf-8')
                    ->set_output(json_encode($data))
                    ->_display();
        exit;
    }

    protected function return_result($result) {
        if($result['status']) {
            $this->success_response($result);
        } else {
            if($result['code'] != -1) {
                $this->fail_response($result, $result['code']);
            }
            $this->fail_response($result);
        }
    }

    protected function check_token() {
        if(!$this->token->check_token($this->accessToken, $this->isAdmin == 'true')) {
            $this->return_result(fail_result('无效的token', null, TOKEN_INVALID));
        }
    }
}
