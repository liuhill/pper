<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2018/12/21
 * Time: 5:27 PM
 */

namespace Controllers\Users;

use Psr\Container\ContainerInterface;
use \Common\Image\Photo;
use \Models\User;
use \Models\Message;

class PhotosController
{
    protected $container;
    protected $resource = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->resource = $container->get("settings")['resource'];
        $this->db = $container->get("db");
    }

    private function getImagesByUid($uid){
        $imgDir = $this->photoDir['resize'] . DIRECTORY_SEPARATOR . $uid;
        $imgUrl = $this->photoDir['resizeUrl'] . DIRECTORY_SEPARATOR . $uid;
        $originalUrl = $this->photoDir['originalUrl'] . DIRECTORY_SEPARATOR . $uid;
        $images = Photo::list($imgDir);
        foreach($images as $i=>$v){
            $images[$i]['url'] = $imgUrl .DIRECTORY_SEPARATOR .$v['name'];
            $images[$i]['original'] = $originalUrl .DIRECTORY_SEPARATOR .$v['name'];
        }
        //echo $images;
        return $images;
    }

    public function index($request, $response, $args){
//        $images = $this->getImagesByUid($uid);


        if(isset($args['id']) && !empty($args['id'])){
            $uid = intval($args['id']);
        }

        if (empty($uid)) {
            $mMessage = new Message($this->db);
            $resouce = $mMessage->getMessage();
        } else {
            $mMessage = new Message($this->db);
            $resouce = $mMessage->getMessage($uid);
        }
        $data = [];
        foreach($resouce as $i=>$v){
            $info = [];
            $info['type'] = $type = $v['type'];
            switch($type){
                case 'text':
                    $info['text'] = $v['content'];
                    break;
                case 'image':
                case 'voice':
                case 'video':
                case 'file':
                    $info['src'] = $v['content'];
                    $info['resource'] = $v['resource'];
                    break;
                default:
                    continue;
            }
            $data[] = $info;
        }

        return $response->withJson($data);
    }

    public function wall($request, $response, $args){
        if(isset($args['id']) && !empty($args['id'])){
            $openId = $args['id'];
            $mUser = new User($this->db);
            $uid = $mUser->getUid($openId);
        }
        else {
            $uid = '';
        }

        $response = $this->view->render($response,
            'wall.phtml',
            [
                'uid' => $uid
            ]);
        return $response;

    }
}