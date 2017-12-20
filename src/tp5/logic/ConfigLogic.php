<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-01 09:38
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\tp5\logic;


use by\infrastructure\helper\CallResultHelper;

class ConfigLogic extends BaseLogic
{
    /**
     * 设置
     * @param $projectId
     * @param $config
     * @return \by\infrastructure\base\CallResult
     */
    public function set($projectId, $config)
    {
        $effects = 0;
        $result = false;
        if ($config && is_array($config)) {
            $flag = true;
            foreach ($config as $name => $value) {
                $map = array('name' => $name, 'project_id' => $projectId);
                $result = $this->getModel()->where($map)->setField('value', $value);
                if ($result !== false) {
                    $effects = $effects + $result;
                } else {
                    $flag = false;
                }
            }
            if ($flag !== false) {
                $result = $effects;
            }
        }

        if ($result === false) {
            return CallResultHelper::fail();
        } else {
            return CallResultHelper::success($result);
        }
    }
}