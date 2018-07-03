<?php
namespace api;
use classes\Db;

class user{
    public $json;
    public function __construct(jsonS $jsonS)
    {
        $this->json = $jsonS;
    }

    /**
     * 用户注册
     */
    public function register(){
        $db = new Db(DB_CON, DB_USER, DB_PWD);
        if(!array_key_exists('username',$_POST) && !array_key_exists('pwd',$_POST)){
            $this->json->msg = '参数不正确';
        }
        $username = $_POST['username'];
        $pwd = $_POST['pwd'];
    }

    /**
     * 添加
     * @param $table
     * @param array $data
     * @return jsonS
     */
    public function inserts(array $data){
        $table = 'member';
        $obj = new \classes\Common($this->db);

        //短信验证码
        if(!array_key_exists('code',$data)){
            $this->json->msg='没有code';
            return $this->json;
        }

        //验证字段
        if(!array_key_exists('username',$data)){
            $this->json->msg='没有username';
            return $this->json;
        }

        $res = $obj->adds($table,array('username'=>$data['username']));

        if(empty($res)){
            $this->json->msg = '加入失败';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }
}