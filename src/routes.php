<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/home','\Controllers\Admin\HomeController:home');
$app->get('/users/{id}','\Controllers\Users\PhotosController:index');
$app->get('/weixin','\Controllers\weixin\IndexController:index');
$app->get('/ttt','\Controllers\weixin\IndexController:ttt');
$app->post('/weixin','\Controllers\weixin\IndexController:index');
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

