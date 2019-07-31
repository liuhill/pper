# pper
 The 3D photo gallery  , Built using Slim 3, Three.js, Tween.js, easychat

# Demo
http://www.pper.com.cn

- Wall
![Wall](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/wall.jpg)

- Cube

![Cube](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/Cube.jpg)

- Sphere

![Sphere](https://raw.githubusercontent.com/liuhill/pper/master/screenshots/Sphere.jpg)

- Cylinder

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


- 7、 Start

```
php -S 0.0.0.0:80 -t public
```