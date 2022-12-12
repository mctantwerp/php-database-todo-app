<?php

function registerExceptionHandler(): void
{
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

/**
 * https://heroicons.com/
 */
function svg(string $name): string
{
    return file_get_contents("./resources/svg/{$name}.svg");
}

function getLine(array $todo): string
{
    return $todo['done'] ? ' line-through' : '';
}
