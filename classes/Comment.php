<?php
namespace classes;

class Comment{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function byUserId($id){
        $sql = "SELECT * FROM `bs2_comment` WHERE user_id=:id";
        return $this->db->selects($sql,[':id'=>$id]);
    }

    public function byVillaId($id){
        $sql = "SELECT * FROM `bs2_comment` WHERE villaid=:id";
        return $this->db->selects($sql,[':id'=>$id]);
    }

    public function inserts($data){
        $sql = "insert into `bs2_comment` (`user_id`,`villaid`,`content`) VALUE (:user,:villa,:content)";
        return $this->db->inserts($sql,$data);
    }
}