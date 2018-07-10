<?php
namespace api;
use classes\Db;
use classes\Member;
use root\start;

class user{
    public $json;
    public function __construct(jsonS $jsonS)
    {
        $this->json = $jsonS;
        $this->db = new Db(DB_CON, DB_USER, DB_PWD);
    }

    /**
     * 手机号是否存在于表中
     * @return jsonS
     */
    public function old_member(){

        $phone = start::getValue('phone');

        if(empty($phone)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        $member = new Member($this->db);

        //已经注册
        $res = $member->getByUsername($phone);

        if(!empty($res)){
            $this->json->msg = '手机号已经注册';
            $this->json->data = $res;
        }else{
            $this->json->msg = '手机号没有注册';
        }

        return $this->json;
    }

    /**
     * 用户名密码登录
     */
    public function login_pwd(){
        $pwd = start::getValue('pwd');
        $phone = start::getValue('phone');
        if(empty($phone)|| empty($pwd)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        //里面改变了json中的data
        $this->old_member();
        if($this->json->data ===''){
            $this->json->msg = '用户不存在';
            $this->json->status = 0;
            return $this->json;
        }

        $userInfo = $this->json->data[0];

        if($userInfo['password']===''){
            $this->json->msg = '请使用验证码登录,并设置密码';
            return $this->json;
        }

        if($userInfo['password'] !== $pwd){
            $this->json->msg = '用户名或密码错误';
            return $this->json;
        }
        session_start();
        unset($userInfo['password']);

        $token = $this->setToken($phone,$userInfo);
        $retData = [
            'token'=>$token,
            'userData'=>$userInfo
        ];

        //覆盖了data
        $this->json->data = $retData;
        return $this->json;
    }

    /**
     * 用户登录,短信验证码登录
     */
    public function login(){

        $phone = start::getValue('phone');
        $code = start::getValue('code');

        if(empty($code) || empty($phone)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        //里面改变了json中的data
        $this->old_member();
        if($this->json->data ===''){
            $this->json->msg = '用户不存在';
            $this->json->status = 0;
            return $this->json;
        }

        //里面改变了json中的data
        if(!$this->checkCode()){
            $this->json->msg = '验证码不正确';
            $this->json->status = 0;
            return $this->json;
        }

        $userInfo = $this->json->data[0];

        $token = $this->setToken($phone,$userInfo);
        $retData = [
            'token'=>$token,
            'userData'=>$this->json->data[0]
        ];

        //覆盖了data
        $this->json->data = $retData;
        return $this->json;
    }

    /**
     * 检查验证码
     * @return jsonS|bool
     */
    private function checkCode(){
        $code = start::getValue('code');
        $phone = start::getValue('phone');
        if(empty($code) || empty($phone)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        session_start();

        $sessionKey = "code{$phone}";

        if(!array_key_exists($sessionKey,$_SESSION) || $_SESSION[$sessionKey]!=$code){
            return false;
        }else{
            return true;
        }
    }

    private function setToken($phone,$userInfo){
        $loginKey = uniqid().$phone.$userInfo['id'];

        $_SESSION[$loginKey] = $userInfo;
        $_SESSION['login_time'] = time();

        $token['username'] = $phone;
        $token['token'] = $loginKey;
        $token['expr'] = 'token有效时间是'.LOGIN_TOKEN_TIME.'秒';
        return $token;
    }

    /**
     * 用户注册
     */
    public function register(){
        $code = start::getValue('code');
        $phone = start::getValue('phone');
        if(empty($code) || empty($phone)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        $member = new Member($this->db);

        //已经注册
        $res = $member->getByUsername($phone);

        if(!empty($res)){
            $this->json->msg = '手机号已经注册';
            return $this->json;
        }

        if(!$this->checkCode()){
            $this->json->msg = '验证码不正确';
            return $this->json;
        }

        $res = $member->register($phone);

        if(empty($res)){
            $this->json->msg='注册失败';
            return $this->json;
        }

        $data = [];
        $data['id'] = $res;
        $data['tel'] = $phone;
        $data['username'] = $phone;

        $token = $this->setToken($phone,$data);

        $this->json->data = $token;
        return $this->json;
    }
}