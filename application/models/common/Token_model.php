<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Model.php';

class Token_model extends Base_Model
{
    public $userInfo = '';

    public function __construct(){
        parent::__construct();
    }

    public function check_token($token, $isAdmin = false) {

        // 检查token是否存在并且是否有效
        $this->userInfo = $this->db->where('access_token', $token)
                    ->from($isAdmin ? TABLE_ADMIN_USER : TABLE_USER)
                    ->get()
                    ->row_array();
        //查得到数据并且token在效期内
        $result = !empty($this->userInfo) && $this->userInfo['token_expiresIn'] >= time();

        return $result;
    }
}