<?php

namespace App\Lib;

use PDO;

class DB
{
    private static $objInstance;

    /**
     * Example usage:
     * $stmt = DB::getInstance()->prepare("SELECT * FROM todos");
     */
    public static function getInstance(): object
    {

        if(!self::$objInstance)
        {
            self::$objInstance = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8", "{$_ENV['DB_USER']}", "{$_ENV['DB_PASS']}");
			self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$objInstance;

    }

    final public static function __callStatic( $chrMethod, $arrArguments )
    {

        $objInstance = self::getInstance();

        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    }
}
