<?php
namespace cache;
class Cache{
    static $path;

    public function __construct()
    {
        if(null == static::$path){
            static::$path = 'temp';
        }
    }

    public static function getPath($k){
        $path = static::$path.$k;
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