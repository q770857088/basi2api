<?php
namespace api;
use classes\Db;
use root\start;

class area{

    public $json;
    public $obj;

    public function __construct(jsonS $jsonS)
    {
        $db = new Db(DB_CON, DB_USER, DB_PWD);
        $this->obj = new \classes\Area($db);

        $this->json = $jsonS;
    }

    public function lists(){

        $linkageId = start::getValue('id');
        //是否传了参数
        if(empty($linkageId)){
            $this->json->msg = '没有传参数id';
            return $this->json;
        }

        $linkageId = intval($linkageId);

        $res = $this->obj->lists($linkageId);

        if(empty($res)){
            $this->json->msg = '数据为空';
            return $this->json;
        }

        $this->json->data = $res;

        return $this->json;
    }

    public function hot(){
        $res = $this->obj->hotList();
        if(empty($res)){
            $this->json->msg = '数据为空';
            return $this->json;
        }
        $this->json->data = $res;
        return $this->json;
    }
}