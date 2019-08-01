# Pper
 The 3D photo gallery  , Built using Slim 3, Three.js, Tween.js, easychat

# Demo
![WeChat](https://raw.githubusercontent.com/liuhill/pper/master/public/images/qrcode_for_gh_15b711976a8a_258.jpg)

http://www.pper.com.cn


- Wall Show

![Wall](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/wall.jpg)

- Cube Show

![Cube](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/Cube.jpg)

- Sphere Show

![Sphere](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/Sphere.jpg)

- Cylinder Show

![Cylinder](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/Cylinder.jpg)

- Plane

![Plane](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/Plane.jpg)



# Installation
- 1、 Download source code
```
git clone https://github.com/liuhill/pper.git
```

- 2、 Install packages
```
composer install
```

- 3、 Generate autoload file
```
composer dump-autoload -o
```


- 4、 Create database `pper`, and import mysql table

```
mysql -uroot -p pper < db/pper.sql
```

- 5、 Copy Default Configure`src/setting.default.php`为`src/setting.php`
```
cp src/setting.default.php src/setting.php
```

- 6、 Configure `src/setting.php`

Wechat
```
        // weixin
        'weixin' => [
            'app_id'    => '',    //app_id
            'secret'    => '',    //EncodingAESKey
            'token'     => '',    //token

        ],
```

mysql
```
        // Database connection settings
        "db" => [
            "host" => "localhost",
            "dbname" => "pper",
            "user" => "",
            "pass" => ""
        ],
```

qiniu cloud

```
        // 七牛云 https://www.qiniu.com/
        'qiniu' => [
            'enable' => false,
            'access' => '',
            'secret' => '',
            'bucket' => ''
        ],
```


- 7、 Start

```
php -S 0.0.0.0:80 -t public
```
OR
```
composer start
```