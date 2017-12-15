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
use think\controller\Rest;
use think\Request;


/**
 * 接口基类
 * Class BaseApiController
 *
 * @author 老胖子-何必都 <hebiduhebi@126.com>
 * @package by\component\Controller
 */
abstract class BaseApiController extends Rest{

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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function readyRequiredParams()
    {
        // 1. 请求客服端版本、类型信息
        $this->allData->setAppType(Request::instance()->param("app_type", ""));
        $this->allData->setAppVersion(Request::instance()->param("app_version", ""));
        if (empty($this->allData->getAppType())) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'app_type']));
        }
        if (empty($this->allData->getAppVersion())) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'app_version']));
        }

        $this->allData->setClientId($this->_param("client_id", "", lang('lack_parameter', ['param' => 'client_id'])));
        if (empty($this->getClientId())) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'client_id']));
        }

        $this->allData->setLang($this->getLang());

        $data = Request::instance()->param();
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

        // 4. 解密数据并转换成 ApiCommonEntity
        $requestParams = $this->transport->decrypt($this->allData->getData());
        if (!array_key_exists('by_api_ver', $requestParams)) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'by_api_ver']));
        }

        if (!array_key_exists('by_type', $requestParams)) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'by_type']));
        }
        if (!array_key_exists('by_notify_id', $requestParams)) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'by_notify_id']));
        }
        if (!array_key_exists('by_time', $requestParams)) {
            $this->apiReturnErr(lang('lack_parameter', ['param' => 'by_time']));
        }

        // 5. 先初始化
        $this->allData->setData([]);
        $this->allData->setClientId($this->getClientId());
        $this->allData->setClientSecret($this->getClientSecret());
        $this->allData->setProjectId($this->getProjectId());
        $this->allData->setLang($this->getLang());
        $this->allData->setAppRequestTime($requestParams['by_time']);
        $this->allData->setNotifyId($requestParams['by_notify_id']);
        $this->allData->setServiceVersion($requestParams['by_api_ver']);
        $this->allData->setServiceType($requestParams['by_type']);
        unset($requestParams['by_time']);
        unset($requestParams['by_notify_id']);
        unset($requestParams['by_api_ver']);
        unset($requestParams['by_type']);
        $this->allData->setData($requestParams);
    }

    public function _param($key, $default='',$emptyErrMsg=''){
        $value = Request::instance()->post($key,$default);
        if($value == $default || empty($value)){
            $value =  Request::instance()->get($key,$default);
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

        $response = $this->response($data, "json",200);
        $siteUrl = config('site_url');
        if (empty($siteUrl)) {
            $siteUrl = "www.itboye.com";
        }
        $notify_id = $this->allData->getNotifyId();
        $notify_id = $notify_id ? $notify_id : time();
        $response->header("X-Powered-By", $siteUrl)->header("X-BY-Notify-ID", $notify_id)->send();
        exit(0);
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _post($key,$default='',$emptyErrMsg=''){

        $value = Request::instance()->post($key,$default);

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
        $value = Request::instance()->get($key,$default);

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

        $value = Request::instance()->header($key);

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