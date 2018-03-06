<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($phone, $userName, $password)
    {
        // 检查用户名是否存在
        $isEx = $this->db->where('phone', $phone)->count_all_results(TABLE_ADMIN_USER);
        if ($isEx) {
            return fail('该手机号已经注册');
        }

        $encrypt = cb_encrypt($password);

        $time = time();

        $data = array(
            'phone' => $phone,
            'user_name' => $userName,
            'password' => $encrypt['password'],
            'salt' => $encrypt['salt'],
            'user_id' => create_id(),
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK,
            'register_time'=> $time
        );

        // 添加数据
        $suc = $this->db->insert(TABLE_ADMIN_USER, $data);

        if (!$suc) {
            return fail('添加管理员失败');
        }

        $adminInfo = $this->db->where('phone', $phone)
                            ->from(TABLE_ADMIN_USER)
                            ->get()
                            ->row_array();

        $userResult = array(
            'userId' => $adminInfo['user_id'],
            'phone' => $adminInfo['phone'],
            'userName' => $adminInfo['user_name'],
            'lastLoginTime' => $adminInfo['last_login_time'],
            'token' => array(
                'accessToken' => $adminInfo['access_token'],
                'tokenExpiresIn' => $adminInfo['token_expiresIn'],
                'exp' => WEEK
            )
        );
        return success($userResult);
    }

    public function login($phone, $password)
    {
        // 检查用户名是否存在
        $isEx = $this->db->where('phone', $phone)->count_all_results(TABLE_ADMIN_USER);
        if ($isEx == 0) {
            return fail('该手机号不存在');
        }

        $adminInfo = $this->db->where('phone', $phone)
                            ->from(TABLE_ADMIN_USER)
                            ->select('user_id, password, phone, user_name, salt, login_count, status')
                            ->get()
                            ->row_array();

        switch($adminInfo['status']) {
            case '1':
                break;
            case '2':
                return fail('该账号已被删除，请先申请恢复账号');
            default:
                return fail('账号异常');
        }

        if (!cb_passwordEqual($adminInfo['password'], $adminInfo['salt'], $password)) {
            return fail('密码错误');
        }

        $time = time();

        $data = array(
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK,
            'login_count' => intval($adminInfo['login_count']) + 1
        );

        // 更新数据
        $this->db->where('user_id', $adminInfo['user_id'])->update(TABLE_ADMIN_USER, $data);

        $adminInfo = $this->db->where('phone', $phone)
                            ->from(TABLE_ADMIN_USER)
                            ->get()
                            ->row_array();

        $userResult = array(
            'userId' => $adminInfo['user_id'],
            'phone' => $adminInfo['phone'],
            'userName' => $adminInfo['user_name'],
            'lastLoginTime' => $adminInfo['last_login_time'],
            'token' => array(
                'accessToken' => $adminInfo['access_token'],
                'tokenExpiresIn' => $adminInfo['token_expiresIn'],
                'exp' => WEEK
            )
        );
                            
        return success($userResult);
    }
}
