<?php
namespace classes;
class Common{

    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * 列表
     * @param $id int 地区的id
     * @return $res
     */
    public function lists($table){
        $sql = "SELECT * FROM `bs2_{$table}`";
        $res = $this->db->selects($sql,[]);
        return $res;
    }

    /**
     * 增加
     */
    public function adds($table,$data){
        $res = $this->db->inserts($table,$data);
        return $res;
    }
}