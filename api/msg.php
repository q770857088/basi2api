<?php
namespace api;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use cache\Cache;
use http\Env\Request;
use root\start;

class msg{


    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        Config::load();

        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile(REGION,ACCESS_KEY_ID,ACCESS_KEY_SECRET);

            // 增加服务结点
            DefaultProfile::addEndpoint(END_POINT_NAME, REGION, PRODUCT, DOMAIN);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    public function __construct(jsonS $jsonS)
    {
        //json
        $this->json = $jsonS;
    }

    public function sendMsg()
    {
        header('Content-Type: text/plain; charset=utf-8');
        session_start();

        $ip = ip2long($_SERVER['REMOTE_ADDR']);
        $current_time = time();

        $times = $current_time-Cache::getCache($ip);
        $this->json->data = $times;

        if($times<2){
            $this->json->msg='请求频繁';
            return $this->json;
        }

        Cache::setCache($ip,$current_time);

        //获取用户手机号
        $tel = start::getValue('phone');
        if(empty($tel)){
            $this->json->msg = '没有传phone';
            return $this->json;
        }

        $sessionKey = 'time'.$tel;

        //上次时间
        if(array_key_exists($sessionKey,$_SESSION) && $current_time-$_SESSION[$sessionKey]<60){
            $this->json->msg='请在一分钟后再发送';
            return $this->json;
        }

        //生成验证码
        $code['code'] = mt_rand('100000','999999');

        //发送验证码
        $response = $this->sendSms($tel,$code);

        //返回结果状态
        $info = $this->object2array($response);
        $info['Code'] = "OK";
        if($info["Code"] == "isv.BUSINESS_LIMIT_CONTROL"){
            $this->json->msg = '发送失败';
            return $this->json;
        }

        if($info["Code"] == "OK"){
            //验证码存入session
            $_SESSION['code'.$tel] = $code['code'];
            $_SESSION['time'.$tel] = time();

            $this->json->msg = '发送成功';
            $this->json->data = $tel;
            return $this->json;
        }

        $this->json->status=0;
        $this->json->data = $response;
        return $this->json;
    }

    /**
     * 发送短信范例
     *
     * @param string $signName <p>
     * 必填, 短信签名，应严格"签名名称"填写，参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/sign">短信签名页</a>
     * </p>
     * @param string $templateCode <p>
     * 必填, 短信模板Code，应严格按"模板CODE"填写, 参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/template">短信模板页</a>
     * (e.g. SMS_0001)
     * </p>
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param array|null $templateParam <p>
     * 选填, 假如模板中存在变量需要替换则为必填项 (e.g. Array("code"=>"12345", "product"=>"阿里通信"))
     * </p>
     * @param string|null $outId [optional] 选填, 发送短信流水号 (e.g. 1234)
     * @return stdClass
     */
    public function sendSms($phoneNumbers, $templateParam = null, $outId = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称
        $signName = SIGN_NAME; // 短信签名
        $request->setSignName($signName);

        // 必填，设置模板CODE
        $templateCode = TEMPLATE_CODE; // 短信模板编号
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam,JSON_UNESCAPED_UNICODE));
        }

        // 可选，设置流水号
        if($outId) {
            $request->setOutId($outId);
        }

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;

    }

    /**
     * 查询短信发送情况范例
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $sendDate 必填，短信发送日期，格式Ymd，支持近30天记录查询 (e.g. 20170710)
     * @param int $pageSize 必填，分页大小
     * @param int $currentPage 必填，当前页码
     * @param string $bizId 选填，短信发送流水号 (e.g. abc123)
     * @return stdClass
     */
    public function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId=null) {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumbers);

        // 选填，短信发送流水号
        $request->setBizId($bizId);

        // 必填，短信发送日期，支持近30天记录查询，格式Ymd
        $request->setSendDate($sendDate);

        // 必填，分页大小
        $request->setPageSize($pageSize);

        // 必填，当前页码
        $request->setCurrentPage($currentPage);

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        // var_dump($acsResponse);

        return $acsResponse;
    }

    public function object2array($object) {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        }else {
            $array = $object;
        }
        return $array;
    }


}