<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2019/1/4
 * Time: 11:36 AM
 */

namespace Models;


class User
{
    public $con;

    public function __construct($db)
    {
        $this->con = $db;
    }


    public function validUser($id){
        $sql = "SELECT * FROM `user` WHERE `id`=$id";
        $row = $this->con->query($sql)->fetchAll();
        if(count($row)>0){
            return $id;
        }
        return false;
    }

    public function getUid($user){
        $isMob='/^1[34578]{1}\d{9}$/';
        $isEmail = '/^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/';
        if(preg_match($isMob,$user)){   //手机号
            echo '手机号用户';

        }
        elseif(preg_match($isEmail,$user))  //邮箱
        {
            echo '邮箱用户';
        }
        else{ //openid或者nickname
            $sql = "SELECT * FROM `user` WHERE `nickname`='$user' OR `openid`='$user'";
            $row = $this->con->query($sql)->fetchAll();
            if(count($row)>0){
                return intval($row[0]['id']);
            }
            return false;
        }

        return false;
    }

    public function getUidByOpenid($openId){
        $uid = $this->getUid($openId);
        if($uid === false){
            $uid = $this->addUser("weixin",$openId);
        }
        return $uid;
    }

    public function addUser($type,$info){

        $ll = $ct = date("Y-m-d H:i:s");
        $db=$this->con;
        switch($type){
            case 'weixin':
                $sql = "INSERT INTO `user`(`openid`,`create_time`,`last_login`) VALUES(:openid,:createtime,:lasttime)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":openid",$info);
                $stmt->bindParam(":createtime",$ct);
                $stmt->bindParam(":lasttime",$ll);
                $stmt->execute();
                $id = $db->lastInsertId();
                break;
            case 'mobile':
                break;
            case 'email':
                break;
            default:
                break;
        }
        return $id;
  }

}