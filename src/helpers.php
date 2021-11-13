<?php

function json_response(array $body = null, int $code = 200): void
{
    header_remove(); // Limpar os headers antigos
    http_response_code($code); // Definir status code
    header('Content-Type: application/json'); // Definir tipo do conteudo

    if ($body) {
        echo json_encode($body, JSON_PRETTY_PRINT);
    }
}