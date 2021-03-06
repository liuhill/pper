<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/home','\Controllers\Admin\HomeController:home');
$app->get('/users/[{id}]','\Controllers\Users\PhotosController:index');
$app->get('/wall/[{id}]','\Controllers\Users\PhotosController:wall');
$app->get('/subscribe','\Controllers\Users\PhotosController:subscribe');
$app->get('/','\Controllers\Users\PhotosController:wall');
$app->get('/weixin','\Controllers\Weixin\IndexController:vaild');
$app->get('/ttt','\Controllers\Weixin\IndexController:ttt');
$app->post('/weixin','\Controllers\Weixin\IndexController:index');
/*
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/

