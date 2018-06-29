<?php

spl_autoload_register(function ($class_name) {
    $arr = explode('\\',$class_name);
    if($arr[0] === 'root'){
        require_once $arr[1].'.php';
    }else{
        require_once $arr[0].DIRECTORY_SEPARATOR.$arr[1].'.php';
    }
});

/**
 * @return \api\jsonS
 */
function gos(){

    //返回的json格式
    $json = new \api\jsonS();

    $app = new \root\start();

    $app->checkHost();

//获取域名
    $server = $_SERVER;

//TODO 干掉$_SERVER
    $host = $server['HTTP_HOST'];

    if($host != HOST){
        $json->msg = '域名有问题';
        return $json;
    }

    if(!array_key_exists('REQUEST_URI',$server)){
        $json->msg = '404';
        return $json;
    }

//请求的接口名称
    $apiName = $server['REQUEST_URI'];

    $arr = explode('/',$apiName);
    $filterArr = array_filter($arr);

    $file_name = array_pop($filterArr);
    $path = 'api/'.$file_name.'.php';


//文件是否存在
    if(!file_exists($path)){
        $json->msg = '接口不存在';
        return $json;
    }

//类名
    $class_name = "\\api\\".$file_name;

    $obj = new $class_name($json);

    if(!array_key_exists('func',$_POST)){
        $json->msg = '没有func参数';
        return $json;
    }

//方法名
    $fun = $_POST['func'];

    if(!method_exists($obj,$fun)){
        $json->msg = '方法不存在';
        return $json;
    }


    $json = $obj->$fun();
    return $json;
}

$obj = gos();
$jsonObj = new \api\returns($obj);
$jsonObj->send();