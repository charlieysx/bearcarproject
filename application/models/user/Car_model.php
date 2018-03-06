<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Car_model extends Base_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function order_car($user_id, $userName, $car_id) {
        $car = $this->db->from(TABLE_CAR)
                            ->where('car_id', $car_id)
                            ->get()
                            ->row_array();

        if (empty($car)) {
            return fail('该车辆不存在');
        }

        if ($car['status'] != '1') {
            return fail('该辆车不在销售中');
        }

        if ($car['user_id'] == $user_id) {
            return fail('不能预约自己的车哦~');
        }

        $isE = $this->db->where('car_id', $car_id)
                            ->where('user_id', $user_id)
                            ->count_all_results(TABLE_USER_ORDER);

        if ($isE) {
            return fail('您已经预约过了，请等待客服联系');
        }

        return fail($userName);
        $userOrder = array(
            'car_id'=> $car_id,
            'user_id'=> $user_id,
            'time'=> time(),
            'user_name'=> $userName
        );

        $this->db->insert(TABLE_USER_ORDER, $userOrder);

        return success('预约成功');
    }
}