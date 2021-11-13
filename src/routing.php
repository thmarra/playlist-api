<?php

function navigate(array $routes)
{
    $controllerDir = 'App\\Controllers\\';

    $path = $_SERVER['PATH_INFO'];
    $method = $_SERVER['REQUEST_METHOD'];
    $body = $_REQUEST;

    //    var_dump($path, $method, $body);

    // Comecar mostrando o GET, que o $body funciona, depois passa pro POST e faz a leitura do input
    $requestBody = file_get_contents('php://input');

    if ($requestBody) {
        $requestBody = json_decode($requestBody, true, flags: JSON_ERROR_NONE);
        $body = array_merge($body, $requestBody);
        // Se a mesma variavel tiver na url e no body o do body substitui por causa da ordem das variaveis
    }

    if (!isset($routes[$method][$path])) {
        json_response(code: 404);
        return;
    }

    [$controllerClass, $func] = $routes[$method][$path];
    $controller = $controllerDir . $controllerClass;

    (new $controller())->$func($body);
}