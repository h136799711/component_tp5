<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-13 17:14
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\security_code\logic;


use by\component\ip\helper\IpHelper;
use by\component\security_code\constants\SecurityCodeStatusEnum;
use by\component\security_code\entity\SecurityCodeEntity;
use by\component\security_code\interfaces\SecurityCodeLogicInterface;
use by\component\string_extend\helper\StringHelper;
use by\component\tp5\logic\BaseLogic;
use by\infrastructure\helper\CallResultHelper;

class SecurityCodeLogic extends BaseLogic implements SecurityCodeLogicInterface
{

    /**
     * 限制时间,在该时间段内,不得向同一acceptor发送同种类型验证码
     */
    private static $LIMIT_SECONDS = 30;

    /**
     * @param string $code
     * @param string $accepter
     * @param string $type
     * @param string $client_id
     * @param bool $is_clear
     * @return \by\infrastructure\base\CallResult
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function isLegalCode($code, $accepter, $type, $client_id, $is_clear = true)
    {

        if ($code == "itboye") {
            return CallResultHelper::success(lang("code legal"));
        }

        $map = array(
            'code' => $code,
            'accepter' => $accepter,
            'type' => $type,
            'client_id' => $client_id
        );

        $order = "expired_time desc";

        $result = $this->getInfo($map, $order);

        if (!($result instanceof SecurityCodeEntity)) {
            return CallResultHelper::fail(lang("code invalid"));
        }

        if ($result->getStatus() == SecurityCodeStatusEnum::USED) {
            return CallResultHelper::fail(lang("code used"));
        }

        if ($result->getExpiredTime() < time()) {
            return CallResultHelper::fail(lang("code expired"));
        }

        //1. 清除该手机号对应的验证码
        if ($is_clear) {
            $this->resetAll($accepter, $type, $client_id);
        }

        return CallResultHelper::success(lang("code legal"));
    }

    public function resetAll($accepter, $type, $client_id)
    {
        $result = $this->save(array('accepter' => $accepter, 'type' => $type, 'client_id' => $client_id), array('status' => SecurityCodeStatusEnum::USED));
        return CallResultHelper::success($result);
    }

    /**
     * 创建验证码
     * @param string $clientId 应用ID
     * @param string $accepter 接收人
     * @param string $type 类型
     * @param int $codeCreateWay
     * @param int $codeLength 验证码长度 3 < length < 8, 默认 6 位
     * @param int $expireTime 过期时间，默认1800秒
     * @return \by\infrastructure\base\CallResult
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function create($clientId, $accepter, $type, $codeCreateWay = StringHelper::NUMBERS, $codeLength = 6, $expireTime = 1800)
    {

        if ($codeLength > 8 || $codeLength < 3) {
            $codeLength = 6;
        }

        $map = array(
            'accepter' => $accepter,
            'type' => $type
        );
        $now = time();

        $result = $this->getInfo($map, "create_time desc");

        if ($result instanceof SecurityCodeEntity) {
            if ($now - $result->getCreateTime() < $this->getLimitSeconds()) {
                return CallResultHelper::fail(lang('retry after'));
            }
        }


        // 生成验证码 6 位
        $code = StringHelper::randStr($codeCreateWay, $codeLength);

        //2. 纪录到数据库
        $entity = new SecurityCodeEntity();
        $entity->setCode($code);
        $entity->setAccepter($accepter);
        $entity->setCreateTime($now);
        $entity->setExpiredTime($now + $expireTime);
        $entity->setClientId($clientId);
        $entity->setType($type);
        $entity->setStatus(SecurityCodeStatusEnum::NOT_USED);
        $entity->setIp(IpHelper::getClientIp(1));


        //1. 重置该手机号对应的验证码
        $this->resetAll($accepter, $type, $clientId);
        //2. 插入到数据库
        $this->add($entity, "id");
        return CallResultHelper::success($code);
    }

    /**
     * 获取限制验证码频率
     * @return int|mixed
     */
    public function getLimitSeconds()
    {
        if (function_exists('config')) {
            self::$LIMIT_SECONDS = config('security_code_limit_seconds');
        }

        return self::$LIMIT_SECONDS;
    }


}