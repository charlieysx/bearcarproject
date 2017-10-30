<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $isDebug = $this->input->get_request_header('isDebug', false);
        if(!$isDebug) {
            $this->fail_response(fail_result("Error No-Debug", null, -1000003), FAIL);
        }
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
}
