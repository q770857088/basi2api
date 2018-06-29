<?php
namespace api;

use classes\Db;

class villa{

    public $json;
    public $obj;
    public function __construct(jsonS $jsonS)
    {
        $this->json =$jsonS;
        $db = new Db(DB_CON, DB_USER, DB_PWD);
        $this->obj = new \classes\Villa($db);
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
}