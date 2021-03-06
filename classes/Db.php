<?php
namespace classes;
class Db{
    private $db;
    public function __construct($con,$user,$pwd){
        $db = new \PDO($con,$user,$pwd);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->exec('set names utf8');
        $this->db = $db;
    }

    /**
     * @param $sql "SELECT * FROM `user` WHERE `login` LIKE :login"
     * @param $arr array(':login'=>'kevin%')
     * @return array
     */
    public function selects($sql,$arr){
        /*查询*/
        $stmt = $this->db->prepare($sql);
        $stmt->execute($arr);
//
//        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
//            print_r($row);
//        }
        return ($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function query($sql){
        /*查询*/
        $stmt = $this->db->prepare($sql);

        return ($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    //删除方法
    public function dels(){
        /*删除*/
        $sql = "DELETE FROM `user` WHERE `login` LIKE 'kevin_'"; //kevin%
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        echo $stmt->rowCount();
    }

    //TODO 更改方法
    public function updates(){
        /*修改*/
        $sql = "UPDATE `user` SET `password`=:password WHERE `user_id`=:userId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':userId'=>'7', ':password'=>'4607e782c4d86fd5364d7e4508bb10d9'));
        echo $stmt->rowCount();
    }

    //插入方法
    public function inserts($table,$data){
        $arrKey = array_keys($data);
        $joinKey = join('`,`',$arrKey);
        $joinKey = "`{$joinKey}`";

        $value = join(',:',$arrKey);
        $value = ":{$value}";

        $sql = "INSERT INTO `bs2_{$table}` ({$joinKey}) VALUE ($value)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastinsertid();
    }
}