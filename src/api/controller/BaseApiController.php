<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace by\component\api\controller;

use by\component\api\entity\ApiCommonEntity;
use by\component\encrypt\interfaces\TransportInterface;
use by\infrastructure\base\CallResult;

use think\Controller;
use think\facade\Request;


/**
 * 接口基类
 * Class BaseApiController
 *
 * @author 老胖子-何必都 <hebiduhebi@126.com>
 * @package by\component\Controller
 */
abstract class BaseApiController extends Controller {

    /**
     * 所有请求的数据
     * 以及可能的全局配置变量
     * @var ApiCommonEntity
     */
    protected $allData;

    /**
     *
     * @var TransportInterface
     */
    protected $transport;


    /**
     * Base constructor.
     */
    public function __construct(){
        $this->allData = new ApiCommonEntity();
        parent::__construct();
    }

    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    abstract function getLang();

    abstract function getProjectId();

    public function getClientId()
    {
        return $this->allData->getClientId();
    }

    abstract function getClientSecret();

    abstract function getApiAlg();

    abstract function getTransport();

    /**
     */
    public function readyRequiredParams()
    {
        // 1. 请求客服端版本、类型信息
        $appType = $this->_param("app_type", "", lang('lack_parameter', ['param' => 'app_type']));
        $appVersion = $this->_param("app_version", "", lang('lack_parameter', ['param' => 'app_version']));
        $clientId = $this->_param("client_id", "", lang('lack_parameter', ['param' => 'client_id']));
        $serviceType = $this->_param("service_type", "", lang('lack_parameter', ['param' => 'service_type']));
        // 2. 以下参数必须传
        $this->allData->setAppType($appType);
        $this->allData->setAppVersion($appVersion);
        $this->allData->setClientId($clientId);
        $this->allData->setServiceType($serviceType);
        // 2. 可以为空
        $this->allData->setServiceVersion(Request::param('service_version', 100));
        $this->allData->setNotifyId(Request::param('notify_id', '0'));

        $data = Request::param();
        $data['client_id'] = $this->getClientId();
        $data['client_secret'] = $this->getClientSecret();
        $this->allData->setData($data);
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function readyAllData()
    {

        $this->transport = $this->getTransport();
        if (!($this->transport  instanceof TransportInterface)) {
            $this->apiReturnErr(lang('invalid client_id'));
        }

        // 4. 解密数据并转换成 ApiCommonEntity
        $requestParams = $this->transport->decrypt($this->allData->getData());

        // 5. 先初始化
        $this->allData->setData([]);
        $this->allData->setClientId($this->getClientId());
        $this->allData->setClientSecret($this->getClientSecret());
        $this->allData->setProjectId($this->getProjectId());
        $this->allData->setLang($this->getLang());
        $this->allData->setData($requestParams);
    }

    public function _param($key, $default='',$emptyErrMsg=''){
        $value = Request::post($key,$default);
        if($value == $default || empty($value)){
            $value =  Request::get($key,$default);
        }

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }


    protected function apiReturnErr($obj, $code = -1, $data = [])
    {
        if ($obj instanceof CallResult) {
            $code = $obj->getCode();
            $data = $obj->getData();
            $obj = $obj->getMsg();
        }

        $this->ajaxReturn(['msg'=>$obj, 'code'=>$code,'data'=>$data,'notify_id'=>$this->allData->getNotifyId()]);
    }


    /**
     * 返回数据
     * @param $data
     */
    protected function ajaxReturn($data) {

        if($this->transport instanceof TransportInterface){
            $data = $this->transport->encrypt($data);
        }


        $siteUrl = config('site_url');
        if (empty($siteUrl)) {
            $siteUrl = "www.itboye.com";
        }
        $notify_id = $this->allData->getNotifyId();
        $notify_id = $notify_id ? $notify_id : time();
        $header = [
            'X-Powered-By'=>$siteUrl,
            "X-BY-Notify-ID"=>$notify_id
        ];
        response($data, 200, $header, "json")->send();
        exit(0);
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _post($key,$default='',$emptyErrMsg=''){

        $value = Request::post($key,$default);

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }

    /**
     * 过滤末尾多余空白符 ASCII码等于7的奇怪符号
     * @param $post
     * @return string
     */
    protected function filter_post($post){
        $post = trim($post);
        for ($i=strlen($post)-1;$i>=0;$i--) {
            $ord = ord($post[$i]);
            if($ord > 31 && $ord != 127){
                $post = substr($post,0,$i+1);
                return $post;
            }
        }
        return $post;
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _get($key,$default='',$emptyErrMsg=''){
        $value = Request::get($key,$default);

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }

    /**
     * 从请求头部获取参数
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg
     * @return string
     */
    public function _header($key,$default='',$emptyErrMsg = ''){

        $value = Request::header($key);

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }

    protected function apiReturnSuc($data){
        $msg = 'success';
        $code = 0;
        if ($data instanceof CallResult) {
            $msg = $data->getMsg();
            $code = $data->getCode();
            $data = $data->getData();
        }

        $this->ajaxReturn(['code'=>$code, 'data'=>$data, 'msg'=>$msg, 'notify_id'=>$this->allData->getNotifyId()]);
    }

}