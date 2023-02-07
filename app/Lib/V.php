<?php

namespace App\Lib;

class V
{
    public static function snippet(string $snippet, array $data = []): void
    {
        $snippet = str_replace('.', '/', $snippet);
        $snippet = __DIR__ . '/../../views/' . $snippet . '.php';

        if(file_exists($snippet))
        {
            extract($data);
            include($snippet);
        }
    }
}
