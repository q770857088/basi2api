<?php
namespace classes;

class Area{

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
    public function lists($id){
        $sql = "SELECT * FROM `bs2_area` WHERE `parentid` = :id";
        $res = $this->db->selects($sql,array('id'=>$id));
        return $res;
    }

    public function hotList(){
        $sql = "SELECT * FROM `bs2_area` WHERE `hot` = :hot";
        $res = $this->db->selects($sql,array('hot'=>1));
        return $res;
    }
}