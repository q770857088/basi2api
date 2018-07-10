<?php
namespace api;
use classes\Db;
use root\start;

class discount{

    public function __construct(Jsons $json)
    {
        $this->db = new Db(DB_CON, DB_USER, DB_PWD);
        $this->json = $json;
    }

    public function lists(){
        $token = start::getValue('token');
        if(empty($token)){
            $this->json->msg='参数错误';
            return $this->json;
        }

        session_start();
        if(!array_key_exists($token,$_SESSION)){
            $this->json->msg = '没有登录';
            return $this->json;
        }

        $userInfo = $_SESSION[$token];
        $userId = $userInfo['id'];

        $obj = new \classes\Discount($this->db);
        $res = $obj->lists($userId);
        $this->json->data = $res;
        return $this->json;
    }
}