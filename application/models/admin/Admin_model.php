<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH. 'core/Base_Model.php';

class Admin_model extends Base_Model
{
    const TABLE_NAME = 'car_admin';

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
            return fail_result('用户名不能为空');
        }

        // 检查用户名是否纯在
        $isEx = $this->db->where('user_name', $opt['userName'])->count_all_results(self::TABLE_NAME);
        if ($isEx) {
            return fail_result('该用户名已经存在，不能重复添加');
        }

        $data = array(
            'user_name' => $opt['userName'],
            'password' => $opt['password'],
            'user_id' => create_id()
        );

        // 添加数据
        $suc = $this->db->insert(self::TABLE_NAME, $data);

        if (!$suc) {
            return fail_result('添加管理员失败');
        }

        return success_result('添加管理员成功');
    }

    /**
     * 登录接口
     * @method login
     * @param  array  $opt [description]
     * @return [type]      [description]
     */
    public function login($opt = array()) {
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
        // 检查用户名是否存在
        $isEx = $this->db->where('user_name', $opt['userName'])->count_all_results(self::TABLE_NAME);
        if (!$isEx) {
            return fail_result('该用户不存在');
        }
        $adminInfo = $this->db->where('user_name', $opt['userName'])
                            ->from(self::TABLE_NAME)
                            ->get()
                            ->result_array();

        $result = array(
            'userId' => $adminInfo[0]['user_id'],
            'password' => $adminInfo[0]['password'],
            'userName' => $adminInfo[0]['user_name']
        );
        if($result['password'] != $opt['password']) {
            return fail_result('密码错误');
        }
        return success_result('登录成功', $result);
    }
}
