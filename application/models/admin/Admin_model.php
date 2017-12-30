<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH. 'core/Base_Model.php';

class Admin_model extends Base_Model
{
    const TABLE_NAME = 'admin_user';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加一个管理员
     * @method add_admin
     * @param  array     $opt [description]
     */
    public function add_admin($opt = array())
    {
        $k = array(
            'userName',
            'password',
        );
        $opt = elements($k, $opt, '');
        // 数据校验
        if ('' == $opt['userName']) {
            return fail_result('登录名不能为空');
        }

        if ('' == $opt['password']) {
            return fail_result('密码不能为空');
        }

        if (strlen($opt['password']) < 6) {
            return fail_result('密码不能少于6位');
        }

        // 检查用户名是否存在
        $isEx = $this->db->where('user_name', $opt['userName'])->count_all_results(self::TABLE_NAME);
        if ($isEx) {
            return fail_result('该用户名已经存在，不能重复添加');
        }

        $encrypt = cb_encrypt($opt['password']);

        $time = time();

        $data = array(
            'user_name' => $opt['userName'],
            'password' => $encrypt['password'],
            'salt' => $encrypt['salt'],
            'user_id' => create_id(),
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK
        );

        // 添加数据
        $suc = $this->db->insert(self::TABLE_NAME, $data);

        if (!$suc) {
            return fail_result('添加管理员失败');
        }

        $adminInfo = $this->db->where('user_name', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->get()
                            ->row_array();

        $userResult = array(
            'userId' => $adminInfo['user_id'],
            'userName' => $adminInfo['user_name'],
            'lastLoginTime' => $adminInfo['last_login_time'],
            'token' => array(
                'accessToken' => $adminInfo['access_token'],
                'tokenExpiresIn' => $adminInfo['token_expiresIn']
            )
        );
        return success_result('添加管理员成功', $userResult);
    }

    /**
     * 登录接口
     * @method login
     * @param  array  $opt [description]
     * @return [type]      [description]
     */
    public function login($opt = array())
    {
        $k = array(
            'userName',
            'password',
        );
        $opt = elements($k, $opt, '');
        // 数据校验
        if ('' == $opt['userName']) {
            return fail_result('用户名不能为空');
        }

        if ('' == $opt['password']) {
            return fail_result('密码不能为空');
        }

        if (strlen($opt['password']) < 6) {
            return fail_result('密码不能少于6位');
        }

        $adminInfo = $this->db->where('user_name', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->select('user_id, password, user_name, salt, login_count, status')
                            ->get()
                            ->row_array();

        if ('' == $adminInfo['user_id']) {
            return fail_result('该用户不存在');
        }

        if(!('1' == $adminInfo['status'])) {
            return fail_result('该账号已被删除，请先申请恢复账号');
        }

        if (!cb_passwordEqual($adminInfo['password'], $adminInfo['salt'], $opt['password'])) {
            return fail_result('密码错误');
        }

        $time = time();

        $data = array(
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK,
            'login_count' => intval($adminInfo['login_count']) + 1
        );

        // 更新数据
        $this->db->where('user_id', $adminInfo['user_id'])->update(self::TABLE_NAME, $data);

        $adminInfo = $this->db->where('user_name', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->get()
                            ->row_array();

        $userResult = array(
            'userId' => $adminInfo['user_id'],
            'userName' => $adminInfo['user_name'],
            'lastLoginTime' => $adminInfo['last_login_time'],
            'token' => array(
                'accessToken' => $adminInfo['access_token'],
                'tokenExpiresIn' => $adminInfo['token_expiresIn']
            )
        );
                            
        return success_result('登录成功', $userResult);
    }
}
