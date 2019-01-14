<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2019/1/14
 * Time: 10:27 AM
 * 七牛云接口
 */

namespace Models;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class Qiniu
{

    private $accessKey, $secretKey, $bucket , $url;

    function __construct( $config )
    {
        $this->accessKey = $config['access'];
        $this->secretKey = $config['secret'];
        $this->bucket = $config['bucket'];
        $this->url = $config['url'];

    }

    public function upload( $src ,$name = null){
        $auth = new Auth($this->accessKey, $this->secretKey);
        $bucketManager = new BucketManager($auth);
        $url = $src ;
        $key = is_null( $name )? "pper_" . md5($src) . ".jpg": $name ;

        // 指定抓取的文件保存名称
        list($ret, $err) = $bucketManager->fetch($url, $this->bucket, $key);
        //echo "=====> fetch $url to bucket: $this->bucket  key: $key\n";
        if ($err !== null) {
            //var_dump($err);
            return false;
        } else {
            //print_r($ret);
            return $this->url . DIRECTORY_SEPARATOR . $ret['key'];
        }
    }

}