<?php
namespace classes;

class Member{

    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getByUsername($username){
        $sql = "SELECT * FROM `bs2_member` WHERE `username`=:username";
        $res = $this->db->selects($sql,[':username'=>$username]);
        return $res;
    }

    public function register($username){
        $res = $this->db->inserts('member',['username'=>$username,'tel'=>$username,'nickname'=>$username]);
        return $res;
    }
}