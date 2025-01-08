<?php

function registerExceptionHandler(): void
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

function get(mixed $input, $default = null): mixed
{
    if (!empty($_POST[$input])) {
        return $_POST[$input];
    }

    if (!empty($_GET[$input])) {
        return $_GET[$input];
    }

    return $default;
}

function snippet(string $name, array $data = []): void
{
    if (!empty($data)) {
        extract($data);
    }

    include "./snippets/{$name}.php";
}
