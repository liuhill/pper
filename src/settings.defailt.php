<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // weixin
        'weixin' => [
            'app_id'    => '',    //测试号
            'secret'    => '',
            'token'     => '',
            'log' => [
                'level' => 'debug',
                'file'  => __DIR__ .  '/../logs/weixin/easywechat.log',
            ]
        ],

        // 图片相关
        'resource' => [
            'path' => __DIR__ . "/../public/resource",
            'url' => "http://".$_SERVER['HTTP_HOST'] . "/resource",
        ],

        // Database connection settings
        "db" => [
            "host" => "localhost",
            "dbname" => "pper",
            "user" => "",
            "pass" => ""
        ],
        // 七牛云
        'qiniu' => [
            'enable' => false,
            'access' => '',
            'secret' => '',
            'bucket' => ''
        ],

    ],
];
