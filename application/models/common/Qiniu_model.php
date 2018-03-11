<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qiniu_model extends Base_Model
{
    // 自己去七牛注册获取
    private $accessKey = "七牛accessKey";
    private $secretKey = "七牛秘钥";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_upload_token($bucket, $token)
    {
        // bearcar修改为自己在七牛创建的
        if($bucket != 'bearcar') {
            return fail_result('bucket 目前只能是 bearcar', '', FAIL);
        }
        $id = md5(uniqid().$token).create_id();
        $deadline = time() + 3600;
        $args['scope'] = $bucket;
        $args['deadline'] = $deadline;
        // 'http://bearcarimg.codebear.cn/'修改为在七牛绑定的域名
        // '!gradual.show'是我的图片样式，修改为自己在七牛上设置的图片样式，或者不添加也行
        $args['returnBody'] = "{\"imgUrl\": \"http://bearcarimg.codebear.cn/".$id."!gradual.show\"}";
        $args['saveKey'] = $id;
        $b = json_encode($args);
        $result = array(
            'key'=> $id,
            'token'=> $this->signWithData($b)
        );
        
        return success($result);
    }

    private function signWithData($data)
    {
        $encodedData = $this->base64_urlSafeEncode($data);
        return $this->sign($encodedData) . ':' . $encodedData;
    }

    private function base64_urlSafeEncode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }

    private function sign($data)
    {
        $hmac = hash_hmac('sha1', $data, $this->secretKey, true);
        return $this->accessKey . ':' . $this->base64_urlSafeEncode($hmac);
    }
}
