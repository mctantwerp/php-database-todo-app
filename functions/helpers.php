<?php

function registerExceptionHandler()
{
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

function svg(string $name): string
{
    return file_get_contents("./resources/svg/{$name}.svg");
}

function getLine(array $todo): string
{
    return $todo['done'] ? ' line-through' : '';
}
