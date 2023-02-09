<?php

namespace App\Lib;

class V
{
    /**
     * Get the filename for the snippet and extra the data array
     *
     * @param string $snippet
     * @param array $data
     *
     * @return void
     */
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
