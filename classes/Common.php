<?php
namespace classes;

class Common{

    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * 地区列表
     * @param $id int 地区的id
     * @return $res
     */
    public function lists($table){
        $sql = "SELECT * FROM `bs2_{$table}`";
        $res = $this->db->selects($sql,[]);
        return $res;
    }
}