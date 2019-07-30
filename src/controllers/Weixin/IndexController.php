<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2018/12/19
 * Time: 4:19 PM
 */

namespace Controllers\Weixin;

use Psr\Container\ContainerInterface;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use \Common\Image\Photo;
use \Models\User;
use \Models\Message;
use \Models\Qiniu;

class IndexController
{
    protected $container;
    protected $db;
    protected $resource;
    private $cfg = null;


    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->cfg = $container->get("settings")['weixin'];
        $this->db = $container->get("db");
        $this->resource = $container->get("settings")['resource'];
    }

    public function vaild($req,$res,$args){
        $app = Factory::officialAccount($this->cfg);
        $response = $app->server->serve();
        // 将响应输出
        return $response->send();
    }

    public function index($req,$res,$args){
        // 使用配置来初始化一个公众号应用实例。
        $app = Factory::officialAccount($this->cfg);

        $app->server->push(function ($message) {
            $user = $message['FromUserName'];
            $mUser = new User($this->db);
            $uid = $mUser->getUidByOpenid($user);
            $userCenter = "http://" . $_SERVER['HTTP_HOST'] . "/wall" .DIRECTORY_SEPARATOR .$uid;

            $mMsg = new Message($this->db);


            switch ( $type = $message['MsgType']) {
                case 'event':
                    if($message['Event'] == "subscribe"){
                        return '欢迎来到拍拍客，请直接发送图片，即可看到自己的照片墙!<br\>输入："我的"或者"mine"可以看到自己的照片墙地址';
                    }
                    elseif($message['Event'] == "unsubscribe"){
                        return '欢迎再次光临，祝您生活愉快!';
                    }

                    return '收到事件消息'.$message['Event'];
                    break;
                case 'text':
                    if ($message['Content'] == "mine" || $message['Content'] == "我的"){
                        return $this->response($userCenter, "" ,"点击进入您的照片墙.或者在浏览器打开: $userCenter", "您的拍拍客地址");
                    }

                    return '收到文字消息:' . $message['Content'];
                    break;
                case 'image':
                    $url = $message['PicUrl'];

                    $this->container->logger->info("收到图片:$url");

                    $imageName = $this->image($url, $uid);
                    if($imageName  === false){
                        return $this->response($url, $url ,$user, "图片操作失败");
                    }
                    $this->container->logger->info("转码成功，$imageName");

                    if($qnUrl = $this->upQiniu($url,"pper_uid$uid"."_".$imageName)){  // 上传到七牛云
                        $orgImage = $qnUrl;
                        $this->container->logger->info("七牛云上传成功，$orgImage");
                        //删除本地图片
                        unlink($this->resource['path'] . DIRECTORY_SEPARATOR . "$uid/original/$imageName");   // 删除本地原图
                        $this->container->logger->info("删除本地成功，".$this->resource['path'] . DIRECTORY_SEPARATOR . "$uid/original/$imageName");
                    }
                    else{
                        $orgImage = $this->resource['url'] . DIRECTORY_SEPARATOR . "$uid/original/$imageName";
                        $this->container->logger->info("上传失败，$orgImage");
                    }

                    $resize = $this->resource['url'] . DIRECTORY_SEPARATOR . "$uid/resize/$imageName";
                    $msg = $mMsg->saveMessage($uid,$type,$resize,$orgImage);
                    $this->container->logger->info("数据库更新失败，$msg");

                    return $this->response($userCenter, $resize,'图片已经上墙，点击或者刷新可以查看最新的照片墙','上墙通知');

                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });
        $response = $app->server->serve();

        // 将响应输出
        return $response->send();
    }


    /**
     * 测试代码使用
     * @param $request
     * @param $response
     * @param $args
     */
    public function ttt($request, $response, $args){
        $url = 'http://mmbiz.qpic.cn/mmbiz_jpg/EuJU30O3lFKuViaDuf1tJ1BLJ4xBgzYE2BsglxyIiaZYblhwFO6icK3iaqQI2wo3jvfKYmWXtz1S860jKicaBYf7mRg/0';
        $openId = 'os_G_jqeNPMfWLA-A2KIKodfm3SY';

        $mUser = new User($this->db);

        $uid = $mUser->getUid($openId);

        $userCenter = "http://".$_SERVER['HTTP_HOST'] . "/users/$uid";
//        return $this->response($url,$userCenter);

        //$this->image($url,$uid);

        $imageName = $this->image($url, $uid);
        if($imageName  === false){
            return $this->response($url, $url ,$openId, "图片操作失败");
        }

        if($qnUrl = $this->upQiniu($url,"pper_uid$uid"."_".$imageName)){  // 上传到七牛云
            unlink($this->resource['path'] . DIRECTORY_SEPARATOR . "$uid/original/$imageName");   // 删除本地原图
            $orgImage = $qnUrl;

        }
        else{
            $orgImage = $this->resource['url'] . DIRECTORY_SEPARATOR . "$uid/original/$imageName";
        }


        //echo $orgImage;
//*/
    }



    private function upQiniu($src,$name){
        $cfg = $this->container->get("settings")['qiniu'];
        if(!$cfg['enable']){
            return false;
        }
        $qn = new Qiniu($cfg);
        $url = $qn->upload($src,$name);
        return $url;
    }


    private function image($url,$uid){
        $dir = $this->container->get("settings")['resource'];
        $originalDir = $dir['path'] . DIRECTORY_SEPARATOR . $uid . DIRECTORY_SEPARATOR . "original";
        $resizeDir = $dir['path'] . DIRECTORY_SEPARATOR . $uid . DIRECTORY_SEPARATOR . "resize";
        $img = Photo::download($url,$originalDir);
        $imageName = basename($img);
        if(file_exists($img)){
            $resieFile = $resizeDir.DIRECTORY_SEPARATOR.$imageName;
            if(Photo::resize($img,$resizeDir.DIRECTORY_SEPARATOR.$imageName) !== true){
                $this->container->logger->error("生成缩略失败:$resieFile");
                return false;
            }
            else {
                return $imageName;
            }
        }
        else{
            $this->container->logger->error("图片下载失败:$url\r\n目标位置: $img");
            return false;
        }
    }

    private function response($url,$image,$desc,$title='拍拍客')
    {
        $items = [
            new NewsItem([
                'title'       => $title,
                'description' => $desc,
                'url'         => $url,
                'image'       => $image,
                // ...
            ]),
        ];
        return $news = new News($items);
    }

}