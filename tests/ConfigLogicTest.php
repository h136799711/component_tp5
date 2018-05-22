<?php
/**
 * Created by PhpStorm.
 * User: itboye
 * Date: 2018/5/16
 * Time: 15:44
 */

namespace tests;


use by\component\paging\vo\PagingParams;
use by\component\tp5\logic\ConfigLogic;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use think\Loader;


// 载入Loader类
require __DIR__ . '/../vendor/topthink/framework/library/think/Loader.php';

class ConfigLogicTest extends TestCase
{
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '127.0.0.1',
        // 数据库名
        'database'    => 'itboye_tbk',
        // 数据库用户名
        'username'    => 'root',
        // 数据库密码
        'password'    => '136799711',
        // 数据库连接端口
        'hostport'    => '3306',
        // 数据库连接参数
        'params'      => [],
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'common_',
    ];

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function testQuery()
    {
        Loader::register();
        $logic = new ConfigLogic(true, $this->connection);
        $map[] = ['type', '=', 1];
        $page = new PagingParams(0, 10);
        $result = $logic->query($map, $page);
        $count = $result['count'];
        var_dump($result);
        Assert::assertEquals(1, $count);
        $map[] = ['type', '=', 1];
        $result = $logic->sum($map, $page);
        var_dump($result);
    }
}