<?php
namespace root;
include '../env.php';
class start{

    public function checkHost(){
    }

    public static function getValue($k){
        $input = file_get_contents("php://input");
        $data = json_decode($input,1);
        if(array_key_exists($k,$data)){
            return $data[$k];
        }else{
            return false;
        }
    }
}