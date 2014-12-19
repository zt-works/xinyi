<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ty
 * Date: 14-6-26
 * Time: 下午4:16
 * To change this template use File | Settings | File Templates.
 */

class Qi_Niu {
    public $bucket = 'xyresources';
    public $access_key = 's56qo8BsfJVdxd1NK5oYzt7TwMzP-j7hp0GkcDZP';
    public $secret_key = 'CBOCvmNLt8qkg41FCw1b4iJvYXfk31ZXrIIV8fYL';

    public $qi_niu_dn="qiniudn.com";
    public $qi_niu_api_host="api.qiniu.com";
    public $qi_niu_handler_url="http://api.qiniu.com/pfop";
    public $qi_niu_handler_path="/pfop";

    public function url_safe_base64_encode($str) // URLSafeBase64Encode
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($str));
    }

    public function url_safe_base64_decode($str)
    {
        $find = array('-', '_');
        $replace = array('+', '/');
        return base64_decode(str_replace($find, $replace, $str));
    }

    public function create_access_token($key){
        $url_eEncode_bucket=urlencode($this->bucket);
        $key_encode_key=urlencode($key);
        $time=time();
        $rand=rand(1,100);
        $save_name=$time."-".$rand;
        $encode_save_as=$this->url_safe_base64_encode($this->bucket.":".$save_name.".m3u8");
        $fPos=urlencode("avthumb/m3u8/segtime/10/preset/video_640k|saveas/$encode_save_as");
        $callback_url=urlencode(admin_url("admin-ajax.php?action=receiveM3u8Url"));

        $query="?bucket=".$url_eEncode_bucket."&key=".$key_encode_key."&fops=".$fPos."&notifyURL=".$callback_url;

        $signing_str=$this->qi_niu_handler_path.$query."\n";

        //$sign=mhash(MHASH_SHA1,$signingStr, $this->secretKey);
        $sign=hash_hmac('sha1', $signing_str, $this->secret_key, true);

        $encoded_sign=$this->url_safe_base64_encode($sign);

        $access_token = $this->access_key.":".$encoded_sign;

        $create_m3u8_url=$this->qi_niu_handler_path.$query;

        return array("accessToken"=>$access_token,"createM3u8Url"=>$create_m3u8_url);
    }

    /**
     * 创建uploadToken时需要返回的值
     * @return mixed|string|void
     */
    public function create_return_body(){
        $return_body=array("scope"=>$this->bucket,"deadline"=>24*60*60+time());

        return json_encode($return_body);
    }

    public function create_upload_token(){

        $encoded_put_policy = $this->url_safe_base64_encode($this->create_return_body());

        //hmac_sha1这个函数没有，用其他函数实现
        //$sign=mhash(MHASH_SHA1,$encodedPutPolicy, $this->secretKey);
        $sign=hash_hmac('sha1', $encoded_put_policy, $this->secret_key, true);

        $encoded_sign =$this->url_safe_base64_encode($sign);

        $upload_token=$this->access_key.":".$encoded_sign.":".$encoded_put_policy;

        return array("uptoken"=>$upload_token);
    }

    /**
     * 发送请求到七牛，返回获取的消息
     * @param $param
     * @return string
     */
    public function send_http($param){
        $response = wp_remote_post( $param["createM3u8Url"], array(
                'timeout' => 30,
                'redirection' => 5,
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array("Host"=>$this->qi_niu_api_host,
                    "Authorization"=>"QBox ".$param["accessToken"],
                    "Content-Type"=>"application/x-www-form-urlencoded"),
                'body' => array(),
                'cookies' => array()
            )
        );

        //获取结果
        //$response_code = wp_remote_retrieve_response_code( $response );
        //$response_message = wp_remote_retrieve_response_message( $response );
        $response_body=wp_remote_retrieve_body($response);

        //error_log(date("[Y-m-d H:i:s]").$response_body,3,get_template_directory()."/log.log");

        return $response_body;
    }

    /**
     * 发送三次请求，返回七牛的persistentId
     * @param $key
     * @return bool
     */
    public function send_http_three($key){
        $param=$this->create_access_token($key);

        //return $this->sendHttp($param);
        for($i=0;$i<3;$i++){
            $return_string=$this->send_http($param);
            if(strpos($return_string,"persistentId")){
                $return=json_decode($return_string);

                return $return->persistentId;
            }
        }

        return false;
    }
}