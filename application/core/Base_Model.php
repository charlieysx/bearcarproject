<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Base_Model extends CI_Model
{
    const USER_TOKEN_TABLE_NAME = 'user';
    const ADMIN_TOKEN_TABLE_NAME = 'admin_user';

    protected $userInfo = '';

    public function __construct(){
        parent::__construct();
        $this->load->helper('array');
        $this->load->database();
    }

    public function check_token($token, $isAdmin) {

        // 检查token是否存在并且是否有效
        $this->userInfo = $this->db->where('access_token', $token)
                    ->from($isAdmin ? self::ADMIN_TOKEN_TABLE_NAME : self::USER_TOKEN_TABLE_NAME)
                    ->get()
                    ->row_array();
        //查得到数据并且token在效期内
        $result = !empty($this->userInfo) && $this->userInfo['token_expiresIn'] >= time();

        return $result;
    }
}
