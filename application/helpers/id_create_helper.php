<?php


function create_id()
{
    $array63 = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                    'a','b','c','d','e','f','g','h','i','j','k','l',
                    'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
                    'A','B','C','D','E','F','G','H','I','J','K','L',
                    'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

    $str = base_convert(md5(uniqid()), 16, 10);

    $result = '';
    $quotient = $str;
    $divisor = $str;
    $flag = true;
    while ($flag) {
        $len = strlen($divisor);
        $pos = 1;
        $quotient = 0;
        $div = substr($divisor, 0, 2);
        $remainder = $div[0];
        while ($pos < $len) {
            $div = $remainder == 0 ? $divisor[$pos] : $remainder.$divisor[$pos];
            $remainder = $div % 63;
            $quotient = $quotient.floor($div / 63);
            $pos++;
        }
        $quotient = trim_left_zeros($quotient);
        $divisor = "$quotient";
        $result = $array63[$remainder].$result;
        if (strlen($divisor) <= 2) {
            if ($divisor<62) {
                $flag = false;
            }
        }
    }
    $result = $array63[$quotient].$result;
    $result = trim_left_zeros($result);
    return $result;
}

function trim_left_zeros($str)
{
    $str = ltrim($str, '0');
    if (empty($str)) {
        $str = '0';
    }
    return $str;
}
