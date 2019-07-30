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
    const  COUNT = 108;
    public $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function getLastMessage(){

    }


    public function getLocal(){
        $sql = "SELECT * FROM message WHERE resource LIKE \"http://www.pper.com.cn%\"";
        return $this->con->query($sql)->fetchAll();
    }


    public function getImages($uid) {
        $rows = $this->getMessage($uid);
        if(count($rows) < Message::COUNT){
            $fillRows = $this->getMessage(1,Message::COUNT - count($rows));
            $rows = array_merge($rows,$fillRows);
        }

        return $rows;
    }

    public function getMessage($uid,$count = Message::COUNT){

        $order = " ORDER  BY create_time DESC limit " . $count;
        if(is_null($uid)) {
            $sql = "SELECT * FROM message " . $order;
        }
        else {
            $sql = "SELECT * FROM message WHERE uid=$uid " . $order;
        }

        //$db->prepare($sql);
        //$db->bindParam(":uid",$uid);

        return $this->con->query($sql)->fetchAll();
    }

    public function updateUrl($url,$id) {
        $sql = "UPDATE message SET resource=:resource WHERE id=:id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":resource", $url);
        $stmt->bindParam(":id", $id);
        $res = $stmt->execute();
        return $res;
    }

    public function saveMessage($uid,$type,$content,$resource){
        $curData = date("Y-m-d H:i:s");
        $db = $this->con;
        $sql = "INSERT INTO `message`(`uid`,`type`,`content`,`resource`,`create_time`) VALUES (:uid,:resouceType,:content,:resource,:createtime)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":uid", $uid);
        $stmt->bindParam(":resouceType", $type);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":resource", $resource);
        $stmt->bindParam(":createtime", $curData);
        $res = $stmt->execute();
        return $res;
    }
}