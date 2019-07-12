<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2019/7/12
 * Time: 3:58 PM
 */

require __DIR__.'/vendor/autoload.php';

use Console\Qn;
use Symfony\Component\Console\Application;

$qn = new Qn();

$application = new Application();
$application->add($qn); // 添加命令行

$application->run();