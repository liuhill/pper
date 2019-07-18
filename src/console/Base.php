<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2019/7/18
 * Time: 1:13 PM
 */

namespace Console;

use PDO;
use Symfony\Component\Console\Command\Command;



class Base  extends Command
{
    public $db;
    public $qiniu;
    public function __construct($settings)
    {

        $dbCfg = $settings['db'];
        $pdo = new PDO("mysql:host=" . $dbCfg['host'] . ";dbname=" . $dbCfg['dbname'],
            $dbCfg['user'], $dbCfg['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db = $pdo;

        $this->qiniu = $settings['qiniu'];
        parent::__construct();
    }

}