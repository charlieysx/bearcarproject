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