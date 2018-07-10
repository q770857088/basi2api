<?php
namespace api;

use classes\Db;
use root\start;

class villa{

    public $json;
    public $obj;
    public $collected;
    public function __construct(jsonS $jsonS)
    {
        $this->json =$jsonS;
        $this->db = $db = new Db(DB_CON, DB_USER, DB_PWD);
        $this->obj = new \classes\Villa($db);

        //初始没有收藏
        $this->collected = false;
    }

    public function lists(){
        $res = $this->obj->lists();
        if(empty($res)){
            $this->json->msg = '没有数据';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }

    /**
     * 别墅收藏
     * @return jsonS
     */
    public function collect(){
        $this->collected();
        if($this->collected){
            return $this->json;
        }

        $token = start::getValue('token');
        if(empty($token)){
            $this->json->msg = '参数错误';
            return $this->json;
        }
        session_start();

        if(!array_key_exists($token,$_SESSION)){
            $this->json->msg = '没有登录';
            return $this->json;
        }

        $villaId = start::getValue('villa');
        if(empty($villaId)){
            return $this->json;
        }

        $userInfo = $_SESSION[$token];
        $data['villa_id'] = $villaId;
        $data['user_id'] = $userInfo['id'];
        $obj  = new \classes\Common($this->db);
        $obj->adds('collect',$data);

        $this->json->status = 1;
        $this->json->msg = '收藏成功';
        return $this->json;
    }

    /**
     * 是否已经收藏
     */
    public function collected(){
        $token = start::getValue('token');
        if(empty($token)){
            $this->json->msg = '参数错误';
            return $this->json;
        }

        session_start();

        if(!array_key_exists($token,$_SESSION)){
            $this->json->msg = '没有登录';
            return $this->json;
        }

        $villaId = start::getValue('villa');
        if(empty($villaId)){
            return $this->json;
        }

        $sql = "select * from bs2_collect WHERE user_id=:uid and villa_id = :villa";
        $ret = $this->db->selects($sql,[
            ':uid'=>$_SESSION[$token]['id'],
            ':villa'=>$villaId
        ]);

        if(empty($ret)){
            $this->collected = false;
            $this->json->msg = '没有收藏过';
            return $this->json;
        }

        $this->collected = true;
        $this->json->msg = '收藏过了';
        return $this->json;
    }

}