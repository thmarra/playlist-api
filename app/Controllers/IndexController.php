<?php

namespace App\Controllers;

class IndexController
{
    public function hello(array $params)
    {
        json_response([
            'message' => $params['text'] ?? 'Hello World'
        ]);
    }
}