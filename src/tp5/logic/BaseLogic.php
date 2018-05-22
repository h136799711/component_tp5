<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-20 11:18
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\tp5\logic;


use by\component\paging\vo\PagingParams;
use by\component\tp5\base\interfaces\BaseLogicInterface;
use by\component\tp5\model\BaseModel;
use by\infrastructure\interfaces\ObjectToArrayInterface;
use think\Exception;
use think\Paginator;

abstract class BaseLogic implements BaseLogicInterface
{
    /**
     * @var BaseModel
     */
    protected $model;

    /**
     * 类似如下使用方式:
     * $logic1 = new BaseLogic(true, "db1");
     * $logic2 = new BaseLogic(true, "db2");
     * @param bool $initModel 是否初始化模型
     * @param null $connection 传入数据库配置 这个会传给model使用
     */
    public function __construct($initModel = true, $connection = null)
    {
        if ($initModel) {
            $clsName = str_replace("Logic", "", get_class($this));
            $clsName = str_replace("logic", "model", $clsName);
            $clsName .= 'Model';
            if (class_exists($clsName)) {
                if (!empty($connection)) {
                    $this->model = new $clsName([], $connection);
                } else {
                    $this->model = new $clsName;
                }
            }
        }
    }

    /**
     * 求和统计
     * @param $map
     * @param $field
     * @return float|int
     */
    public function sum($map, $field)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        return $this->getModel()->where($map)->sum($field);
    }

    /**
     * get model
     * @return BaseModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * set Model
     * @param BaseModel $model
     */
    public function setModel(BaseModel $model)
    {
        $this->model = $model;
    }

    /**
     * 数量统计
     * @param $map
     * @param bool $field
     * @return int|string
     */
    public function count($map, $field = false)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        if ($field === false) {
            $result = $this->model->where($map)->count();
        } else {
            $result = $this->model->where($map)->count($field);
        }
        return $result;
    }

    /**
     * 禁用
     * 必须有status字段 ，0 为禁用状态
     * @param $map
     * @return integer|false 更新条数
     */
    public function disable($map)
    {
        return $this->save($map, array('status' => 0));
    }

    /**
     * 保存
     * @param $map
     * @param $entity
     * @return integer|false 更新条数
     */
    public function save($map, $entity)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        return $this->getModel()->save($entity, $map);
    }

    /**
     * 启用
     * 必须有status字段，1 为启用状态
     * @param $map
     * @return integer|false 更新条数
     */
    public function enable($map)
    {
        return $this->save($map, array('status' => 1));
    }

    /**
     * 假删除
     * 必须有status字段，且 －1 为删除状态
     * @param $map
     * @return integer|false 更新条数
     */
    public function pretendDelete($map)
    {
        return $this->save($map, array('status' => -1));
    }

    /**
     * 根据id保存数据
     * @param $id
     * @param $entity
     * @return integer|false 更新条数
     */
    public function saveByID($id, $entity)
    {
        unset($entity['id']);

        return $this->save(['id' => $id], $entity);
    }

    /**
     * 数字类型字段有效
     * @param $map array 条件
     * @param $field string 更改字段
     * @param float|int $cnt float 增加的值
     * @return integer 返回影响记录数 或 错误信息
     * @throws Exception
     */
    public function setInc($map, $field, $cnt = 1)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        return $this->model->where($map)->setInc($field, $cnt);
    }

    /**
     * 数字类型字段有效,不允许小于0,维护字段最小为0,金额等敏感类型不适用
     * @param $map array 条件
     * @param $field string 更改字段
     * @param $cnt int 减少的值
     * @return integer 返回影响记录数 或 错误信息
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setDecCantZero($map, $field, $cnt = 1)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        $result = $this->model->where($map)->find()->toArray();

        if (!empty($result) && isset($result[$field])) {
            $fieldValue = $result[$field];
            if ($fieldValue - $cnt < 0) $cnt = $fieldValue;
        }

        return $this->setDec($map, $field, $cnt);

    }

    /**
     * 数字类型字段有效
     * @param $map array 条件
     * @param $field string 更改字段
     * @param $cnt int 减少的值 减少的值
     * @return integer|string 返回影响记录数 或 错误信息
     * @throws Exception
     */
    public function setDec($map, $field, $cnt = 1)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        return $this->model->where($map)->setDec($field, $cnt);
    }

    /**
     * 批量更新，仅根据主键来
     * @param $entity
     * @return array
     * @throws \Exception
     */
    public function saveAll($entity)
    {
        return $this->model->saveAll($entity);
    }

    /**
     * 获取数据find
     * @param $map
     * @param bool $order
     * @param bool $field
     * @return array|false|null|object|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInfo($map, $order = false, $field = false)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        $query = $this->model;

        if (false !== $order) {
            $order = $this->parseOrder($order);
            $query = $query->order($order);
        }

        if (false !== $field) {
            $query = $query->field($field);
        }

        $result = $query->where($map)->find();

        if ($result instanceof BaseModel) {
            return $result->toEntity();
        }

        return $result;
    }

    /**
     * 删除
     * @map 条件
     * @param $map
     * @return int
     */
    public function delete($map)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        return $this->model->where($map)->delete();
    }


    /**
     * 批量删除
     * @param $data
     * @return int
     */
    public function bulkDelete($data)
    {
        return $this->getModel()->destroy($data);
    }

    /**
     * add 添加
     * 支持 类传入
     * @param $entity
     * @param $pk string 主键
     * @return bool | int
     * @throws Exception
     */
    public function add($entity, $pk = 'id')
    {
        if ($entity instanceof ObjectToArrayInterface) {
            $entity = $entity->toArray();
        }

        if (!is_array($entity)) {
            throw new Exception('parameter invalid');
        }

        $result = $this->model->data($entity)->isUpdate(false)->save();
        if (!empty($pk)) {
            $result = $this->getInsertId($pk);
        }
        return $result;
    }

    public function getInsertId($pk = 'id')
    {
        return $this->model->$pk;
    }

    /**
     * 批量插入
     * @param array $list 数组
     * @return array
     * @throws \Exception
     */
    public function addAll($list)
    {
        return $this->model->saveAll($list);
    }

    /**
     * query 不分页
     * @param $map array 查询条件
     * @param $order boolean|string 排序条件
     * @param $fields boolean|string 只获取指定字段
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function queryNoPaging($map = null, $order = false, $fields = false)
    {
        $map = $this->preProcessMapFroTp5Dot1($map);
        $order = $this->parseOrder($order);
        $query = $this->model;
        if (!empty($map)) $query = $query->where($map);
        if (false !== $order) $query = $query->order($order);
        if (false !== $fields) $query = $query->field($fields);
        $list = $query->select();
        $entityList = [];
        foreach ($list as $vo) {
            if ($vo instanceof BaseModel) {
                array_push($entityList, $vo->toEntity());
            }
        }
        return $entityList;
    }


    /**
     * query
     * @param array $map 查询条件
     * @param PagingParams $page 分页参数
     * @param bool $order 排序参数
     * @param bool $fields
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query($map, PagingParams $page, $order = false, $fields = false)
    {
        $query = $this->getModel();
        $map = $this->preProcessMapFroTp5Dot1($map);
        $order = $this->parseOrder($order);
        if (!is_null($map)) $query = $query->where($map);
        if (false !== $order) $query = $query->order($order);
        if (false !== $fields) $query = $query->field($fields);

        $start = max(intval($page->getPageIndex()) - 1, 0) * intval($page->getPageSize());
        $list = $query->limit($start, $page->getPageSize())->select();

        $count = $this->getModel()->where($map)->count();
        $entityList = [];
        foreach ($list as $vo) {
            if ($vo instanceof BaseModel) {
                array_push($entityList, $vo->toEntity());
            }
        }

        return ["count" => $count, "list" => $entityList];
    }

    /**
     * query
     * @param array $map
     * @param PagingParams $page
     * @param bool $order
     * @param bool|array $params
     * @param bool $fields
     * @return Paginator
     * @throws \think\exception\DbException
     */
    public function queryWithPagingHtml($map, PagingParams $page, $order = false, $params = [], $fields = false)
    {
        $query = $this->model;
        $map = $this->preProcessMapFroTp5Dot1($map);
        $order = $this->parseOrder($order);
        if (!is_null($map)) $query = $query->where($map);
        if (false !== $order) $query = $query->order($order);
        if (false !== $fields) $query = $query->field($fields);
        $config = [
            'page' => $page->getPageIndex(),
            'list_rows' => $page->getPageSize(),
            'query' => $params
        ];
        $list = $query->paginate($config);
        return $list;
    }

    /**
     * 针对5.0的字符串排序方式兼容5.1 的升级辅助方法
     * @param $order
     * @return array
     */
    public function parseOrder($order)
    {
        if (empty($order)) return $order;
        if (is_array($order)) return $order;
        $tmp = explode(",", $order);
        $newOrder = [];
        foreach ($tmp as $val) {
            $one = $this->parseOneOrder($val);
            $newOrder = array_merge($newOrder, $one);
        }
        return $newOrder;
    }

    private function parseOneOrder($one)
    {
        $one = preg_split("/\s+/", trim($one));
        if (count($one) == 2 && ($one[1] == 'desc' || $one[1] == 'asc')) {
            return [$one[0]=>$one[1]];
        }
        return $one[0];
    }

    /**
     * 针对5.0的字符串查询方式兼容5.1的升级辅助方法
     * 全部转成索引数组方式
     * @param $map
     * @return array
     */
    public function preProcessMapFroTp5Dot1($map)
    {

        $newMap = [];
        foreach ($map as $key => $value) {
            if (is_int($key)) {
                $newMap[] = $value;
                continue;
            }
            if (is_array($value)) {
                array_unshift($value, $key);
                $newMap[] = $value;
            } else {
                $newMap[] = [$key, '=', $value];
            }
        }
        return [$newMap];
    }
}