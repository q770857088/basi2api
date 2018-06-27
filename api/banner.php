<?php
namespace api;
use classes\Banner;
use classes\Db;

$db = new Db();
$banner = new Banner($db);

$typeArr = ['pc'=>1,'wap'=>'0'];

$param = $_POST['type'];
if(array_key_exists($param,$typeArr)){
    $type = $typeArr[$param];
}else{
    echo json_encode(array('status'=>0,'msg'=>'类型不存在'));
    exit;
}


$res = $banner->lists($type);

echo json_encode($res);