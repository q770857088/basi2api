<?php
namespace api;
use classes\Db;

/**
 * 公用
 * Class common
 * @package api
 */
class common{

    public $json;
    public $arr;
    public function __construct(jsonS $jsonS)
    {
        $this->arr = [
            //活动表
            'act'=>'activity',
            'act_villa'=>'activityvilla',
            'area'=>'area',
        ];
        $this->json = $jsonS;
    }

    /**
     * Banner列表
     * @return jsonS
     */
    public function lists(){
        $db = new Db(DB_CON, DB_USER, DB_PWD);
        $obj = new \classes\Common($db);

        if(array_key_exists('table',$_POST)){
            $tableKey = $_POST['table'];
        }else{
            $this->json->msg= '需要参数table';
            return $this->json;
        }

        if(array_key_exists($tableKey,$this->arr)){
            $table = $this->arr[$tableKey];
        }else{
            $this->json->msg='table参数错误';
            return $this->json;
        }

        $res = $obj->lists($table);

        if(empty($res)){
            $this->json->msg = '数据为空';
            return $this->json;
        }

        $this->json->data = $res;
        return $this->json;
    }
}