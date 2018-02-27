<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller
{
    protected $accessToken = '';
    protected $isAdmin = false;
    protected $super = false;

    public function __construct() 
    {
        parent::__construct();

        $this->load->model('common/Token_model', 'token');

        $this->accessToken = $this->input->get_request_header('accessToken', '');
    }

    protected function success_response($data = array(), $status_code = SUCCESS)
    {
        if($data == null) {
            $data = success_result();
        }

        $this->output->set_status_header($status_code)
                    ->set_header('Content-Type: application/json; charset=utf-8')
                    ->set_output(json_encode($data))
                    ->_display();
        exit;
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
        if($result['success']) {
            $this->return_success($result['msg']);
        } else {
            $this->return_fail($result['msg']);
        }
    }

    protected function return_success($result, $success_msg = 'success') 
    {
        $result = success_result($success_msg, $result);
        $this->success_response($result);
    }

    protected function return_fail($msg, $code = -1) 
    {
        $result = fail_result($msg, '', $code);

        if($code != -1) {
            $this->fail_response($result, $code);
        }
        $this->fail_response($result);
    }

    protected function check_token() {
        if(!$this->token->check_token($this->accessToken, $this->isAdmin)) {
            $this->return_fail('无效的token', TOKEN_INVALID);
        }
        $this->userId = $this->token->userInfo['user_id'];
        if($this->isAdmin && $this->token->userInfo['type'] == '1') {
            $this->super = true;
        }
    }
}
