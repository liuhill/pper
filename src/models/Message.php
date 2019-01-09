<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2019/1/4
 * Time: 11:36 AM
 */

namespace Models;


class Message
{
    const  COUNT = 144;
    public $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function getLastMessage(){

    }

    public function getMessage($uid = 1){

        $order = " ORDER  BY create_time DESC limit " . Message::COUNT;
        $sql = "SELECT * FROM message WHERE uid=$uid " . $order;
        //$db->prepare($sql);
        //$db->bindParam(":uid",$uid);
        return $this->con->query($sql)->fetchAll();
    }

    public function saveMessage($uid,$type,$content,$resource){
        $curData = date("Y-m-d H:i:s");
        $db = $this->con;
        $sql = "INSERT INTO `message`(`uid`,`type`,`content`,`resource`,`create_time`) VALUES (:uid,:rsouceType,:content,:resource,:createtime)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":uid", $uid);
        $stmt->bindParam(":rsouceType", $type);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":resource", $resource);
        $stmt->bindParam(":createtime", $curData);
        $res = $stmt->execute();
        return $res;
    }
}