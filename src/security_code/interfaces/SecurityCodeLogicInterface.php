<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-10-25 13:40
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\security_code\interfaces;


interface SecurityCodeLogicInterface
{
    /**
     * 验证验证码是否有效
     * @param string $code     验证码
     * @param string $accepter 接收者
     * @param string $type 类型
     * @param string $client_id 应用id
     * @param bool $is_clear 是否清除同类型验证码
     */
    public function isLegalCode($code, $accepter, $type, $client_id, $is_clear = true);


    /**
     * 标记指定的验证码为已使用
     * @param $accepter
     * @param $type
     * @param $client_id
     * @return mixed
     */
    public function resetAll($accepter, $type, $client_id);
}