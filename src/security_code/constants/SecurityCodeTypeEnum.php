<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-14 16:06
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\security_code\constants;


class SecurityCodeTypeEnum
{

    /**
     * 注册
     */
    const TYPE_FOR_REGISTER = 1;

    /**
     * 更新密码
     */
    const TYPE_FOR_UPDATE_PSW = 2;

    /**
     * 绑定手机号,之前未绑定过
     */
    const TYPE_FOR_NEW_BIND_PHONE = 3;

    /**
     * 更换手机号,
     */
    const TYPE_FOR_CHANGE_NEW_PHONE = 4;

    /**
     * 用于登录
     */
    const TYPE_FOR_LOGIN = 5;
    /**
     * 找回密码
     */
    const TYPE_FOR_FOUND_PSW = 6;

    public static function getTypeDesc($type)
    {
        switch ($type) {
            case SecurityCodeTypeEnum::TYPE_FOR_CHANGE_NEW_PHONE:
                return lang('change new phone');
            case SecurityCodeTypeEnum::TYPE_FOR_NEW_BIND_PHONE:
                return lang('bind new phone');
            case SecurityCodeTypeEnum::TYPE_FOR_REGISTER:
                return lang('register');
            case SecurityCodeTypeEnum::TYPE_FOR_UPDATE_PSW:
                return lang('update password');
            case SecurityCodeTypeEnum::TYPE_FOR_LOGIN:
                return lang('login');
            case SecurityCodeTypeEnum::TYPE_FOR_FOUND_PSW:
                return lang('found password');
            default:
                return false;
        }
    }
}