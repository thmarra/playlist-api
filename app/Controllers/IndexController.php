<?php

namespace App\Controllers;

class IndexController
{
    public function hello(array $params): void
    {
        json_response([
            'message' => $params['text'] ?? 'Hello World'
        ]);
    }
}