<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2018/12/20
 * Time: 4:12 PM
 * 对curl的操作进行优化
 */

namespace Common\Curl;

define('CURL_TIMEOUT',30);

class Curlex
{
    /**
     * @param $url
     * @param $dest : 保存目标文件
     * @return array|bool
     *
     * 如果成功返回true
     * 否则：返回错误信息[错误代码=>错误信息]
     */
    static public function get($url,$dest){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        $errorInfo = self::valid(curl_errno($ch));
        curl_close($ch);

        if($errorInfo === true){
            //$filename = pathinfo($url, PATHINFO_BASENAME);
            $resource = fopen($dest, 'a');
            fwrite($resource, $file);
            fclose($resource);
            return true;
        }
        else{
            return $errorInfo;
        }

    }


    /**
     * @param $errorNo
     * @return array|bool
     * curl的错误信息处理
     * 如果为0则返回true
     * 否则：返回错误信息[错误代码=>错误信息]
     *
     */
    static public function valid($errorNo){
        if(!$errorNo){
            return true;
        }
        $iniFile = __DIR__ . DIRECTORY_SEPARATOR . "error.ini";
        if(file_exists($iniFile)){
            $errorInfo = parse_ini_file($iniFile);
            $msg = $errorInfo[$errorNo];
            return "$errorNo:$msg";
        }
        else{
            return "$errorNo:未知错误信息编码";
        }
    }
}