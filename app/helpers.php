<?php
/**
 * Get the svg icon
 * https://heroicons.com/
 * @param string $name
 *
 * @return string
 */
function svg(string $name): string
{
    return file_get_contents("./resources/svg/{$name}.svg");
}

/**
 * Add strikethrough to todo if it is done
 * @param App\Models\Todo $todo
 *
 * @return string
 */
function getLine(App\Models\Todo $todo): string
{
    return $todo->isDone() ? ' line-through' : '';
}
