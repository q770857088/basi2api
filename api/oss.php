<?php
namespace api;
// | 加载系统类
use OSS\Core\OssException;
use OSS\OssClient;
use root\start;

class Oss
{
    public function __construct(jsonS $jsonS)
    {
        $this->json = $jsonS;
    }

    /**
     * 配置OSS
     * @return OssClient|bool
     */
    public static function ossClient()
    {
        //配置OSS 参数
        $accessKeyId = ACCESS_KEY_ID;
        $accessKeySecret = ACCESS_KEY_SECRET;
        $endpoint = ENDPOINT;
        $bucket = BUCKET;
        //实例化OSS
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        if (is_null($ossClient)){
            return false;
        }else{
            return $ossClient;
        }
    }

    /**
     * oss简单上传
     * @param $object
     * @param $filename
     * @return null
     */
    public  function ossUpload($object,$filename)
    {
        //实例化OSS
        $ossClient = self::ossClient();

        if(empty($ossClient)){
            $this->json->msg = '实例化Oss错误';
        }

        //oss bucket
        $bucket = BUCKET;

        $result = $ossClient->uploadFile($bucket,$object,$filename);
        return $result;

    }


    public  function getKey(){
        $arr = [
            'user'=>USER_OSS_DIR,
            'discover'=>DISCOVER_OSS_DIR,
            'area'=>AREA_OSS_DIR,
        ];

        /**
         * 上传图片的位置
         */
        $dirKey = start::getValue('pos');

        if(empty($dirKey)){
            $this->json->msg = '参数不正确';
            return $this->json;
        }

        function gmt_iso8601($time) {
            $dtStr = date("c", $time);
            $mydatetime = new \DateTime($dtStr);
            $expiration = $mydatetime->format(\DateTime::ISO8601);
            $pos = strpos($expiration, '+');
            $expiration = substr($expiration, 0, $pos);
            return $expiration."Z";
        }

        $id= ACCESS_KEY_ID;
        $key= ACCESS_KEY_SECRET;

        $bucket = BUCKET;
        $endpoint = ENDPOINT;

        $host = "http://{$bucket}.{$endpoint}";

        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = gmt_iso8601($end);

        $dir = $arr[$dirKey];

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        $this->json->data = $response;
        return $this->json;
    }

    /**
//     * 删除object
//     *
//     * @param OssClient $ossClient OSSClient实例
//     * @param string $bucket bucket名字
//     * @return null
     */
    public  function deleteObject($object)
    {
//        $ossClient = self::ossClient();
//        $bucket = BUCKET;
//        try{
//            $ossClient->deleteObject($bucket, $object);
//        } catch(OssException $e) {
//            $msg = (__FUNCTION__ . ": FAILED\n").($e->getMessage() . "\n");
//            return $msg;
//        }
//        return true;
    }

    /**
    **
    * 判断object是否存在
    *
    * @param OssClient $ossClient OSSClient实例
    * @param string $bucket bucket名字
    * @return null
    */
    public static function doesObjectExist($object)
    {
        $ossClient = self::ossClient();
        $bucket = BUCKET;
        try{
            //true/FALSE
            $exist = $ossClient->doesObjectExist($bucket, $object);
        } catch(OssException $e) {
            return false;
        }

        return $exist;
    }

    public function imageExist(){
        $img = start::getValue('img');
        if(empty($img)){
            $this->json->msg = '参数错误';
            return $this->json;
        }

        //返回bool值,true或FALSE
        $ossRes = self::doesObjectExist($img);
        if($ossRes){
            $this->json->data = 1;
            $this->json->msg = '存在';
            $this->json->status = 1;
        }else{
            $this->json->data = 0;
            $this->json->msg = '不存在';
            $this->json->status = 0;
        }
        return $this->json;
    }

}
