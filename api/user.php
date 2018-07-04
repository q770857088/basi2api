<?php
namespace api;
use classes\Db;
use classes\Member;

class user{
    public $json;
    public function __construct(jsonS $jsonS)
    {
        $this->json = $jsonS;
        $this->db = new Db(DB_CON, DB_USER, DB_PWD);
    }

    /**
     * 用户注册
     */
    public function register(){
        if(!array_key_exists('code',$_POST) || !array_key_exists('phone',$_POST)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        $phone = $_POST['phone'];

        $member = new Member($this->db);

        //已经注册
        $res = $member->getByUsername($phone);

        if(!empty($res)){
            $this->json->msg = '手机号已经注册';
            return $this->json;
        }

        $code = $_POST['code'];

        session_start();

        $sessionKey = "code{$phone}";

        if(!array_key_exists($sessionKey,$_SESSION) || $_SESSION[$sessionKey]!=$code){
            $this->json->msg = '验证码不正确';
            return $this->json;
        }

        $res = $member->register($phone);

        if(empty($res)){
            $this->json->msg='注册失败';
            return $this->json;
        }

        $loginKey = uniqid().$phone.$res;

        $_SESSION[$loginKey] = $res;
        $_SESSION['login_time'] = time();

        $userInfo['username'] = $phone;
        $userInfo['token'] = $loginKey;
        $userInfo['expr'] = 'token有效时间是'.LOGIN_TOKEN_TIME.'秒';
        $this->json->data = $userInfo;
        return $this->json;
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