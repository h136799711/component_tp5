<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-17
 * Time: 9:35
 */

namespace by\component\tp5\helper;


use think\Exception;

class ExceptionHelper
{
    public static function getErrorString(Exception $ex)
    {
        return 'trace:' . $ex->getTraceAsString() . 'file:' . $ex->getFile() . ';line:' . $ex->getLine() . ';msg:' . $ex->getMessage();
    }
}