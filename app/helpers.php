<?php
/**
 * https://heroicons.com/
 */
function svg(string $name): string
{
    return file_get_contents("./resources/svg/{$name}.svg");
}

function getLine(App\Models\Todo $todo): string
{
    return $todo->isDone() ? ' line-through' : '';
}
