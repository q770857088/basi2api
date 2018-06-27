<?php

spl_autoload_register(function ($class_name) {
    $arr = explode('\\',$class_name);
    if($arr[0] === 'root'){
        require_once $arr[1].'.php';
    }else{
        require_once $arr[0].DIRECTORY_SEPARATOR.$arr[1].'.php';
    }
});

$app = new \root\start();

$app->checkHost();

//获取域名
$server = $_SERVER;

//TODO 干掉$_SERVER
$host = $server['HTTP_HOST'];

if($host != HOST){
    return json_encode(array('status'=>0,'msg'=>'域名有问题'));
}

//请求的接口名称
$apiName = $server['argv'];

$index = array_pop(array_filter(explode('/',$apiName[0])));
$path = 'api/'.$index.'.php';

if(file_exists($path)){
    require_once($path);
}else{
    echo json_encode(array('status'=>0,'msg'=>'接口不存在'));
    exit;
}

