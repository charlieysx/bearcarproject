<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH. 'core/Base_Model.php';

class User_model extends Base_Model
{
    const TABLE_NAME = 'user';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 批量添加用户，暂时开放
     */
    public function add($opt = array()) {
        $k = array(
            'userName',
            'password'
        );
        $opt = elements($k, $opt, '');
        // 数据校验
        if ('' == $opt['userName']) {
            return fail_result('手机号码不能为空');
        }
        
        if (!is_phone($opt['userName'])) {
            return fail_result('手机号格式错误');
        }

        if ('' == $opt['password']) {
            return fail_result('密码不能为空');
        }

        if (strlen($opt['password']) < 6) {
            return fail_result('密码不能少于6位');
        }

        // 检查用户名是否存在
        $isEx = $this->db->where('phone', $opt['userName'])->count_all_results(self::TABLE_NAME);
        if ($isEx) {
            return fail_result('该手机号已经注册');
        }

        $encrypt = cb_encrypt($opt['password']);

        $time = time();

        $data = array(
            'phone' => $opt['userName'],
            'password' => $encrypt['password'],
            'salt' => $encrypt['salt'],
            'user_id' => create_id(),
            'last_login_time' => $time,
            'access_token' => create_id(),
            'token_expiresIn' => $time + WEEK
        );

        // 添加数据
        $suc = $this->db->insert(self::TABLE_NAME, $data);
    }

    /**
     * 添加一个用户
     * @method add_user
     * @param  array     $opt [description]
     */
    public function add_user($opt = array())
    {
        $k = array(
            'userName',
            'password'
        );
        $opt = elements($k, $opt, '');
        // 数据校验
        if ('' == $opt['userName']) {
            return fail_result('手机号码不能为空');
        }
        
        if (!is_phone($opt['userName'])) {
            return fail_result('手机号格式错误');
        }

        if ('' == $opt['password']) {
            return fail_result('密码不能为空');
        }

        if (strlen($opt['password']) < 6) {
            return fail_result('密码不能少于6位');
        }

        // 检查用户名是否存在
        $isEx = $this->db->where('phone', $opt['userName'])->count_all_results(self::TABLE_NAME);
        return fail_result($isEx);
        if ($isEx) {
            return fail_result('该手机号已经注册');
        }

        $encrypt = cb_encrypt($opt['password']);

        $time = time();

        $data = array(
            'phone' => $opt['userName'],
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
            return fail_result('注册失败');
        }

        $adminInfo = $this->db->where('phone', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->get()
                            ->row_array();
    
        $userResult = array(
            'userId' => $adminInfo['user_id'],
            'userName' => $adminInfo['phone'],
            'lastLoginTime' => $adminInfo['last_login_time'],
            'token' => array(
                'accessToken' => $adminInfo['access_token'],
                'tokenExpiresIn' => $adminInfo['token_expiresIn'],
                'exp' => WEEK
            )
        );
        return success_result('注册成功', $userResult);
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
            'password'
        );
        $opt = elements($k, $opt, '');
        // 数据校验
        if ('' == $opt['userName']) {
            return fail_result('手机号不能为空');
        }

        if (!is_phone($opt['userName'])) {
            return fail_result('手机号格式错误');
        }

        if ('' == $opt['password']) {
            return fail_result('密码不能为空');
        }

        if (strlen($opt['password']) < 6) {
            return fail_result('密码不能少于6位');
        }

        $adminInfo = $this->db->where('phone', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->select('user_id, password, phone, salt, login_count, status')
                            ->get()
                            ->row_array();

        if ('' == $adminInfo['user_id']) {
            return fail_result('该手机号不存在');
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

        $adminInfo = $this->db->where('phone', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->get()
                            ->row_array();

        $userResult = array(
            'userId' => $adminInfo['user_id'],
            'userName' => $adminInfo['phone'],
            'lastLoginTime' => $adminInfo['last_login_time'],
            'token' => array(
                'accessToken' => $adminInfo['access_token'],
                'tokenExpiresIn' => $adminInfo['token_expiresIn'],
                'exp' => WEEK
            )
        );
                            
        return success_result('登录成功', $userResult);
    }
}
