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
use by\component\tp5\base\exception\BusinessException;
use by\component\tp5\helper\ExceptionHelper;
use by\infrastructure\base\CallResult;
use think\Exception;

abstract class BaseIndexController extends BaseApiController
{
    private $domainName;
    private $methodName;

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


    /**
     * 接口入口
     */
    public function index()
    {
        try {

            // 已登录会话ID
            $module = $this->getModuleName();
            $serviceType = $this->allData->getServiceType();

            $api_type = preg_replace("/_/", "/", substr(trim($serviceType), 3), 1);
            $api_type = preg_split("/\//", $api_type);

            if (count($api_type) < 2) {
                $this->apiReturnErr("type参数不正确!", BaseErrorCode::Invalid_Parameter);
            }

            $action_name = $api_type[1];
            $controller_name = $api_type[0];
            $this->domainName = $controller_name;
            $this->methodName = $action_name;

            if ($module == 'default') {
                $module = "domain";
            } else {
                $module = $module . "_domain";
            }

            $cls_name = "by\\$module\\" . $controller_name . 'Domain';
            if (!class_exists($cls_name, true)) {
                $this->apiReturnErr(lang('err_404'), BaseErrorCode::Not_Found_Resource);
            }

            // 3. 初始化业务类
            $object = new $cls_name($this->transport, $this->allData);

            if (!method_exists($object, $action_name)) {
                $this->apiReturnErr('api-' . lang('err_404'), BaseErrorCode::Not_Found_Resource);
            }

            // 4. 调用方法, 反射注入参数
//            $callResult = ReflectionHelper::invokeWithArgs($object, $action_name, $this->allData);
            $callResult = $object->$action_name();
            if ($callResult instanceof CallResult) {
                if ($callResult->isSuccess()) {
                    $this->apiReturnSuc($callResult);
                } else {
                    $this->apiReturnErr($callResult);
                }
            }

            throw new BusinessException($cls_name . $action_name . ' 必须返回CallResult对象');
        } catch (BusinessException $businessException) {
            $this->apiReturnErr($businessException->getMessage(), $businessException->getCode());
        } catch (Exception $ex) {
            $this->apiReturnErr(ExceptionHelper::getErrorString($ex), BaseErrorCode::Business_Error);
        }
    }

    /**
     * 获取接口模块名称
     * 1. 用于未来对接口进行业务拆分、按使用场景进行拆分  比如针对第三方的接口、针对PC的接口
     * @return string
     */
    private function getModuleName()
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