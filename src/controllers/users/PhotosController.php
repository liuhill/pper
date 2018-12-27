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
        $this->photoDir = $container->get("settings")['photo'];
    }

    public function index($req,$res,$args){
        $uid = $args['id'];
        $imgDir = $this->photoDir['resize'] . DIRECTORY_SEPARATOR . $uid;
        $imgUrl = $this->photoDir['resizeUrl'] . DIRECTORY_SEPARATOR . $uid;
        $images = Photo::list($imgDir);
        foreach($images as $i=>$v){
            $images[$i]['url'] = $imgUrl .DIRECTORY_SEPARATOR .$v['name'];
        }
        //echo $images;
        return $res->withJson($images);
    }
}