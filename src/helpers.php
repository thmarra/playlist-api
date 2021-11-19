<?php

function navigate(array $routes)
{
    $controllerDir = 'App\\Controllers\\';

    $path = $_SERVER['PATH_INFO'] ?? null;
    $method = $_SERVER['REQUEST_METHOD'];
    $body = $_REQUEST;

    // var_dump($path, $method, $body);

    // Comecar mostrando o GET, que o $body funciona, depois passa pro POST e faz a leitura do input
    $requestBody = file_get_contents('php://input');

    if ($requestBody) {
        $requestBody = json_decode($requestBody, true, flags: JSON_ERROR_NONE);
        $body = array_merge($requestBody, $body);
        // Se a mesma variavel tiver na url e no body o da url substitui por causa da ordem das variaveis
    }

    if (!isset($routes[$path][$method])) {
        json_response(code: 404);
        return;
    }

    [$controllerClass, $func] = $routes[$path][$method];
    $controller = $controllerDir . $controllerClass;

    (new $controller())->$func($body);
}

function json_response(array $body = null, int $code = 200): void
{
    header_remove(); // Limpar os headers antigos
    http_response_code($code); // Definir status code
    header('Content-Type: application/json'); // Definir tipo do conteudo

    if ($body) {
        echo json_encode($body, JSON_PRETTY_PRINT);
    }
}