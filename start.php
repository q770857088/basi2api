<?php
namespace root;
include '../env.php';
class start{

    public function checkHost(){
    }

    public static function getValue($k){
        if(array_key_exists($k,$_POST)){
            return $_POST[$k];
        }else{
            return false;
        }
    }
}