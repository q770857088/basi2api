<?php
namespace api;
use classes\Db;
use root\start;

class banner{

    public $json;
    public function __construct(jsonS $jsonS)
    {
        $this->json = $jsonS;
    }

    /**
     * Banner列表
     * @return jsonS
     */
    public function lists(){
        $db = new Db(DB_CON, DB_USER, DB_PWD);
        $banner = new \classes\Banner($db);

        $typeArr = ['pc'=>0,'wap'=>'1'];
        $param = start::getValue('type');
        if(empty($param)){
            $this->json->msg = '请传参数type';
            return $this->json;
        }

        if(array_key_exists($param,$typeArr)){
            $type = $typeArr[$param];
        }else{
            $this->json->msg = '类型不存在';
            return $this->json;
        }

        $res = $banner->lists($type);

        if(empty($res)){
            $this->json->msg='数据为空';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }
}