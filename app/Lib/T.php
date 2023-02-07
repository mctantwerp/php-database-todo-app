<?php

namespace App\Lib;

class T
{
    public static function registerExceptionHandler(): void
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    public static function load(string $controller, $args = [])
	{
		return call_user_func(new $controller(), $args);
	}
}
