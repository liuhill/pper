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


class PhotosController
{
    protected $container;
    protected $photoDir = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->photoDir = $container->get("settings")['photo'];
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
        $uid = $args['id'];
        $images = $this->getImagesByUid($uid);
        return $response->withJson($images);
    }

    public function wall($request, $response, $args){
        $uid = $args['id'];
        $response = $this->view->render($response,
            'wall.phtml',
            [
                'uid' => $uid
            ]);
        return $response;

    }
}