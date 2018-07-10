<?php
namespace classes;

class Discount{

    public function __construct(Db $db)
    {
        $this->db=$db;
    }

    public function lists($user){
        $sql = "SELECT `dl`.`id`,`dl`.`status`,`dl`.`etime`,`d`.`title`,`d`.`price`,`d`.`bgimg`
                  FROM `bs2_dislists` AS `dl` LEFT JOIN `bs2_discount` AS `d` ON `dl`.`dis_id`=`d`.`id` 
                  WHERE `dl`.`user_id`=:user";
        $res = $this->db->selects($sql,[':user'=>$user]);
        return $res;
    }
}