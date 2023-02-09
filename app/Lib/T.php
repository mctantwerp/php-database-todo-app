<?php

namespace App\Lib;

class T
{
    /**
     * Show our own pretty error pages
     *
     * @return void
     */
    public static function registerExceptionHandler(): void
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    /**
     * Load a controller
     * Needed to trigger _invoke for single action controllers
     *
     * @param string $controller
     * @param array $args
     */
    public static function load(string $controller, $args = [])
	{
		return call_user_func(new $controller(), $args);
	}
}
