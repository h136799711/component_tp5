<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-15
 * Time: 15:05
 */

namespace by\component\tp5\base\exception;


use think\exception\Handle;

class JsonExceptionHandler extends Handle
{
    public function render(\Exception $e)
    {

        return parent::render($e);
    }

}