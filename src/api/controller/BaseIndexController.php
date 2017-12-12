<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-12 15:45
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\api\controller;


use by\component\api\constants\BaseErrorCode;
use by\component\helper\ReflectionHelper;
use by\infrastructure\base\CallResult;

abstract class BaseIndexController extends BaseApiController
{
    private $domainName;
    private $methodName;
    private $domainObject;

    /**
     * @return mixed
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @return mixed
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    protected function getDomainObject()
    {

        // 已登录会话ID
        $module = $this->getModuleName();
        $serviceType = $this->allData->getServiceType();

        $api_type = preg_replace("/_/", "/", substr(trim($serviceType), 3), 1);
        $api_type = preg_split("/\//", $api_type);

        if (count($api_type) < 2) {
            $this->apiReturnErr("type参数不正确!", BaseErrorCode::Invalid_Parameter);
        }

        $this->methodName = $api_type[1];
        $this->domainName = $api_type[0];

        if ($module == 'default') {
            $module = "domain";
        } else {
            $module = $module . "_domain";
        }

        $clsName = "by\\$module\\" . $this->domainName . 'Domain';
        if (!class_exists($clsName, true)) {
            $this->apiReturnErr(lang('err_404'), BaseErrorCode::Not_Found_Resource);
        }

        // 3. 初始化业务类
        $object = new $clsName($this->transport, $this->allData);

        if (!method_exists($object, $this->methodName)) {
            $this->apiReturnErr('api-' . lang('err_404'), BaseErrorCode::Not_Found_Resource);
        }

        $this->domainObject = $object;
    }

    protected function callMethod()
    {
        // 4. 调用方法, 反射注入参数
        $callResult = ReflectionHelper::invokeWithArgs($this->domainObject, $this->getMethodName(), $this->allData->getData());
//        $methodName = $this->getMethodName();
//        $callResult = $this->domainObject->$methodName();
        if ($callResult instanceof CallResult) {
            if ($callResult->isSuccess()) {
                $this->apiReturnSuc($callResult);
            } else {
                $this->apiReturnErr($callResult);
            }
        }
    }


    /**
     * 获取接口模块名称
     * 1. 用于未来对接口进行业务拆分、按使用场景进行拆分  比如针对第三方的接口、针对PC的接口
     * @return string
     */
    protected function getModuleName()
    {
        $data = $this->allData->getData();
        $module_name = isset($data['by_m']) ? ($data['by_m']) : "";
        if (empty($module_name)) $module_name = "default";
        return $module_name;
    }

    /**
     * 获取登录会话id
     * @return string
     */
    protected function getLoginSId()
    {
        $data = $this->allData->getData();
        $login_s_id = isset($data['s_id']) ? ($data['s_id']) : "";
        return $login_s_id;
    }

    /**
     *  获取用户UID
     *
     */
    protected function getUid()
    {
        $data = $this->allData->getData();
        $uid = array_key_exists('uid', $data) ? $data['uid'] : 0;
        return intval($uid);
    }

}