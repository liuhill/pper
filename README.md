# pper2
拍拍客2.0 ,基于slim3.0和easychat搭建

# 安装
- 1 下载代码
```
git clone https://github.com/liuhill/pper2.git
```

- 2 安装组件
```
composer install
```

- 3 生成自动加载
```
composer dump-autoload -o
```


- 4 创建mysql数据库`pper`,并导入数据表

```
mysql -uroot -p pper < db/pper.sql
```

- 5 复制配置文件`src/setting.default.php`为`src/setting.php`
```
cp src/setting.default.php src/setting.php
```

- 6 修改微信配置和数据库配置，文件 `src/setting.php`

微信
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


- 4 启动服务
在根目录下执行
```
php -S 0.0.0.0:80 -t public
```