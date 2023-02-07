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
}
