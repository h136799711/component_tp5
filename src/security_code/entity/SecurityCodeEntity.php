<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-10-25 13:44
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\security_code\entity;


use by\infrastructure\base\BaseEntity;

class SecurityCodeEntity extends BaseEntity
{
    private $code;
    private $accepter;
    private $expiredTime;
    private $ip;
    private $clientId;
    private $status;
    private $type;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getAccepter()
    {
        return $this->accepter;
    }

    /**
     * @param string $accepter
     */
    public function setAccepter($accepter)
    {
        $this->accepter = $accepter;
    }

    /**
     * @return mixed
     */
    public function getExpiredTime()
    {
        return $this->expiredTime;
    }

    /**
     * @param mixed $expiredTime
     */
    public function setExpiredTime($expiredTime)
    {
        $this->expiredTime = $expiredTime;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}