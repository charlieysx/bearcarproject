<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class test extends Base_Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function test() {
        $orderNumber = 'BC'.date("YmdHis", '1519956174').'0000000001';
        $this->return_success($orderNumber);
    }
}
