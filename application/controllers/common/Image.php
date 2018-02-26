<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Controller.php';

class Image extends Base_Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function get_image() {
        $param = $this->input->get();
        $k = array(
          'imageUrl'
        );
        $opt = elements($k, $param, '');

        if ($opt['imageUrl'] == '') {
          exit;
        }
        @ header("Content-Type:image/*");

        $url = '';
        foreach($param as $k) {
            $url = $url.$k;
        }

        echo file_get_contents($url);
    }
}
