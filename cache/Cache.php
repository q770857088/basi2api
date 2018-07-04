<?php
namespace cache;
class Cache{
    public function __construct()
    {
    }

    public static function getPath($k){
        $path = ROOT_DIR.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
        $path = $path.$k;
        return $path;
    }

    public static function setCache($k,$v){
        $path = self::getPath($k);
        $ret = file_put_contents($path,$v);
        return $ret;
    }

    public static function getCache($k){
        $path = self::getPath($k);
        $ret = file_get_contents($path);
        return $ret;
    }
}