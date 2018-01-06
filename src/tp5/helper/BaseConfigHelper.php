<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-10
 * Time: 17:17
 */

namespace by\component\tp5\helper;

use by\component\tp5\entity\ConfigEntity;
use by\component\tp5\logic\ConfigLogic;
use think\Cache;
use think\Config;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class BaseConfigHelper
{

    const GLOBAL_CONFIG_CACHE_NAME = 'by_tp5_g_config';

    public static function __callStatic($name, $arguments)
    {
        return Config::get($name);
    }

    public static function isDebug()
    {
        return Config::get('app_debug');
    }

    /**
     * 获取配置信息，不存在则返回false
     * @param $projectId
     * @param $key
     * @return false
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getValue($projectId, $key)
    {
        return self::getConfig($projectId, $key);
    }

    /**
     * 读取配置值
     * @param $projectId
     * @param $name
     * @return false 或 object
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    static public function getConfig($projectId, $name)
    {
        $config = self::initGlobalConfig($projectId);
        if (isset($config[$name])) {
            return $config[$name];
        }

        return Config::get($name);
    }

    /**
     * 初始化全局配置信息
     * @param $projectId
     * @param int $cacheTime
     * @return array|bool|mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    static public function initGlobalConfig($projectId, $cacheTime = 86400)
    {
        $config = Cache::get(self::GLOBAL_CONFIG_CACHE_NAME . $projectId);
        if (empty($config)) {
            $map = array();
            $fields = 'type,name,value';
            $api = new ConfigLogic();
            $map['project_id'] = $projectId;
            $result = $api->queryNoPaging($map, false, $fields);
            $config = array();

            if (is_array($result)) {
                foreach ($result as $cfg) {
                    if ($cfg instanceof ConfigEntity) {
                        $config[$cfg->getName()] = self::parse($cfg->getType(), $cfg->getValue());
                    }
                }
            }

            // 缓存配置$cacheTime秒
            Cache::set(self::GLOBAL_CONFIG_CACHE_NAME . $projectId, $config, $cacheTime);
        }

        return $config;
    }

    /**
     * 根据配置类型解析配置
     * @param  integer $type 配置类型
     * @param  string $value 配置值
     * @return array|string
     */
    public static function parse($type, $value)
    {
        switch ($type) {
            case 3 :
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if (strpos($value, ':')) {
                    $value = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val, 2);
                        $value[$k] = $v;
                    }
                } else {
                    $value = $array;
                }
                break;
        }
        return $value;
    }

    /**
     * 配置初始化
     *
     * @param $projectId
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function init($projectId)
    {
        $config = self::initGlobalConfig($projectId);
        foreach ($config as $key => $value) {
            Config::set($key, $value);
        }
    }

    public static function clear()
    {
        Cache::clear();
    }
}