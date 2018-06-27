<?php
namespace classes;
use classes\Db;
class Banner{

    private $db;
    public function __construct(Db $db)
    {
       $this->db = $db;
    }

    /**
     * 获得Banner的列表
     * @param $type String Banner的类型
     * @return array
     */
    public function lists($type){
        $sql = 'SELECT * FROM `bs2_banner` WHERE `type` = :type';

        $res = $this->db->selects($sql,array('type'=>$type));

        return $res;
    }
}