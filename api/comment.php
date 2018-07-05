<?php
namespace api;

use classes\Db;

class comment{

    public function __construct(jsonS $jsonS)
    {
        $this->db = new Db(DB_CON, DB_USER, DB_PWD);
        $this->json = $jsonS;
    }

    public function villa_ids(){

        if(!array_key_exists('id',$_POST)){
            $this->json->msg = 'id is null';
            return $this->json;
        }

        $comment = new \classes\Comment($this->db);
        $res = $comment->byVillaId($_POST['id']);
        if(empty($res)){
            $this->json->msg = '暂无数据';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }

    public function user_ids(){

        if(!array_key_exists('token',$_POST)){
            $this->json->msg = 'token is null';
            return $this->json;
        }

        $token = $_POST['token'];

        session_start();
        $current_time = time();

        if(!array_key_exists($token,$_SESSION)){
            $this->json->msg = '未登录';
            return $this->json;
        }

        $id = $_SESSION[$token];
        $old = $_SESSION['login_time'];
        if($current_time-$old>LOGIN_TOKEN_TIME){
            unset($_SESSION['login_time']);
            unset($_SESSION[$token]);

            $this->json='登录超时';
            return $this->json;
        }

        $_SESSION['login_time'] = time();

        $comment = new \classes\Comment($this->db);
        $res = $comment->byUserId($id);
        if(empty($res)){
            $this->json->msg = '暂无数据';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }
}