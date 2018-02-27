<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($phone, $password)
    {
        // 检查用户名是否存在
        $isEx = $this->db->where('phone', $phone)->count_all_results(TABLE_USER);
        if ($isEx) {
            return fail('该手机号已经注册');
        }

        $encrypt = cb_encrypt($password);

        $time = time();

        $data = array(
            'phone' => $phone,
            'password' => $encrypt['password'],
            'salt' => $encrypt['salt'],
            'user_id' => create_id(),
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK
        );

        // 添加数据
        $suc = $this->db->insert(TABLE_USER, $data);

        if (!$suc) {
            return fail('注册失败');
        }

        $userInfo = $this->db->where('phone', $phone)
                            ->from(TABLE_USER)
                            ->get()
                            ->row_array();
    
        $userResult = array(
            'userId' => $userInfo['user_id'],
            'phone' => $userInfo['phone'],
            'lastLoginTime' => $userInfo['last_login_time'],
            'token' => array(
                'accessToken' => $userInfo['access_token'],
                'tokenExpiresIn' => $userInfo['token_expiresIn'],
                'exp' => WEEK
            )
        );
        return success($userResult);
    }

    public function login($phone, $password)
    {
        // 检查用户名是否存在
        $isEx = $this->db->where('phone', $phone)->count_all_results(TABLE_USER);
        if ($isEx == 0) {
            return fail('该手机号不存在');
        }

        $userInfo = $this->db->where('phone', $phone)
                            ->from(TABLE_USER)
                            ->select('user_id, password, phone, salt, login_count, status')
                            ->get()
                            ->row_array();

        switch($userInfo['status']) {
            case '1':
                break;
            case '2':
                return fail('该账号已被删除，请先申请恢复账号');
            default:
                return fail('账号异常');
        }

        if (!cb_passwordEqual($userInfo['password'], $userInfo['salt'], $password)) {
            return fail('密码错误');
        }

        $time = time();

        $data = array(
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK,
            'login_count' => intval($userInfo['login_count']) + 1
        );

        // 更新数据
        $this->db->where('user_id', $userInfo['user_id'])->update(TABLE_USER, $data);

        $userInfo = $this->db->where('phone', $phone)
                            ->from(TABLE_USER)
                            ->get()
                            ->row_array();

        $userResult = array(
            'userId' => $userInfo['user_id'],
            'phone' => $userInfo['phone'],
            'lastLoginTime' => $userInfo['last_login_time'],
            'token' => array(
                'accessToken' => $userInfo['access_token'],
                'tokenExpiresIn' => $userInfo['token_expiresIn'],
                'exp' => WEEK
            )
        );
                            
        return success($userResult);
    }
}
