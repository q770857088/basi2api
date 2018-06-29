<?php
namespace classes;

class Villa{

    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function lists(){
        $sql = 'select * from bs2_villabasic ORDER BY `creat_time`';
        $res  =$this->db->selects($sql,[]);
        return $res;
    }
}