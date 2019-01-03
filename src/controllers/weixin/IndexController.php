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

class IndexController
{
    protected $container;
    private $cfg = null;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->cfg = $container->get("settings")['weixin'];
    }

    public function index($req,$res,$args){
        // 使用配置来初始化一个公众号应用实例。
        $app = Factory::officialAccount($this->cfg);

        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息:' . $message['content'];
                    break;
                case 'image':
                    $user = $message['FromUserName'];
                    $url = $message['PicUrl'];
                    $newUrl = $this->image($url,$user);
                    $userCenter = "http://".$_SERVER['HTTP_HOST'] . "/user/$user";
                    return $this->response($userCenter,$newUrl);
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

    public function ttt($request, $response, $args){

        $url = 'http://mmbiz.qpic.cn/mmbiz_jpg/EuJU30O3lFLtYk4iaia1FMDrPc8JCzOuA1VbGbdX2ibypHkA92vJicuYKAyxKh21vsZrVxROdoRpnKGT5ic7n94ZDSw/0';
        $user = 'os_G_jqeNPMfWLA-A2KIKodfm3S';
        $userCenter = "http://".$_SERVER['HTTP_HOST'] . "/user/$user";
        return $this->response($url,$userCenter);

        //$this->image($url,$name);
    }

    private function image($url,$user){
        $dir = $this->container->get("settings")['photo'];
        $originalDir = $dir['original'].DIRECTORY_SEPARATOR.$user;
        $resizeDir = $dir['resize'].DIRECTORY_SEPARATOR.$user;
        $img = photo::download($url,$originalDir);
        $imageName = basename($img);
        if(file_exists($img)){
            $resieFile = $resizeDir.DIRECTORY_SEPARATOR.$imageName;
            if(photo::resize($img,$resizeDir.DIRECTORY_SEPARATOR.$imageName) !== true){
                $this->container->logger->error("生成缩略失败:$resieFile");
                return false;
            }
            else {
                return $dir['resizeUrl']."/$user/$imageName";
            }
        }
        else{
            $this->container->logger->error("图片下载失败:$url\r\n目标位置: $img");
            return false;
        }
    }

    private function response($url,$image,$desc=null,$title='图片上墙')
    {
        if(is_null($desc)){
            $desc = "图片已经上墙，点击查看" ;
        }
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