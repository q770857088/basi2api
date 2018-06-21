<?php
include 'start.php';
//单入口文件

$app = new \root\start();

$app->checkHost();

//获取域名
$server = $_SERVER;

//TODO 干掉$_SERVER
$host = $server['HTTP_HOST'];


if($host != HOST){
    return json_encode(array('status'=>0,'msg'=>'域名有问题'));
}
