<?php
namespace api;

use classes\Db;
use root\start;

class comment{

    public function __construct(jsonS $jsonS)
    {
        $this->db = new Db(DB_CON, DB_USER, DB_PWD);
        $this->json = $jsonS;
    }

    /**
     * 根据别墅id获得评论
     * @return jsonS
     */
    public function villa_ids(){
        $id = start::getValue('id');

        if(empty($id)){
            $this->json->msg = 'id is null';
            return $this->json;
        }

        $comment = new \classes\Comment($this->db);
        $res = $comment->byVillaId($id);
        if(empty($res)){
            $this->json->msg = '暂无数据';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }

    /**
     * 根据用户id得到评论
     * @return jsonS|string
     */
    public function user_ids(){

        $token = start::getValue('token');
        if(empty($token)){
            $this->json->msg = 'token is null';
            return $this->json;
        }

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

    /**
     * 添加评论
     */
    public function inserts(){
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

        $content = start::getValue('content');
        if(empty($content)){
            $this->json->msg = '参数错误';
            return $this->json;
        }

        $villaId = start::getValue('villa_id');
        if(empty($villaId)){
            $this->json->msg = '参数错误';
            return $this->json;
        }

        $image = start::getValue('image');
        if(empty($image)){
            $this->json->msg = '参数错误';
            return $this->json;
        }
        if(!is_array($image)){
            $this->json->msg = '图片必须是数组';
            return $this->json;
        }

        //判断图片是否存在
        foreach($image as $k=>$v){
            if(Oss::doesObjectExist($v)===false){
                $this->json->msg="图片'{$v}'不存在";
                return $this->json;
            }
        }

        $userInfo = $_SESSION[$token];

        $data['user_id'] = $userInfo['id'];
        $data['username'] = $userInfo['username'];
        $data['content'] = $content;
        $data['villaid'] = $villaId;
        $data['image'] = implode(',',$image);

        $comment = new \classes\Comment($this->db);

        $id = $comment->inserts($data);
        if(empty($id)){
            $this->json->msg = '插入失败';
            return $this->json;
        }

        $this->json->status = 1;
        $this->json->msg = '评论成功';
        $this->json->data = $id;
        return $this->json;
    }

    /**
     * 给评论点赞
     */
    public function hit(){
        $commentId = start::getValue('comment_id');
        if(empty($commentId)){
            $this->json->msg = '参数错误';
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

        $userInfo = $_SESSION[$token];

        $data = [];
        $data['u_id'] = $userInfo['id'];
        $data['com_id'] = intval($commentId);
        $hitObj = new \classes\Common($this->db);
        $hitObj->adds('comhits',$data);

        $this->json->status = 1;
        $this->json->msg = '点赞成功';
        $this->json->data = $commentId;
        return $this->json;
    }
}