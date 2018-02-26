<?php

/**
 * 判断是否是手机号
 */
function is_phone($phone) {
    if (preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 从请求参数中获取page和pageSize的值（列表才有）
 */
function get_page($params, $maxPageSize = 99, $defaultPageSize = 15) {
    $k = array(
        'page',
        'pageSize'
    );
    $opt = elements($k, $params, '');
    if($opt['page'] == '' || intval($opt['page']) < 0) {
        $opt['page'] = '0';
    }
    if($opt['pageSize'] == '' || intval($opt['pageSize']) > $maxPageSize || intval($opt['pageSize']) <= 0) {
        $opt['pageSize'] = $defaultPageSize;
    }
    $opt['page'] = intval($opt['page']);
    $opt['pageSize'] = intval($opt['pageSize']);
    return $opt;
}