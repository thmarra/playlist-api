<?php

namespace App\Controllers;

class IndexController
{
    public function hello(array $params)
    {
        echo $params['text'];
    }
}