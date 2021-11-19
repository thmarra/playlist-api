<?php

function routes(): array
{
    return [
        '/api/test' => [
            'GET' => ['IndexController', 'hello']
        ],
        '/api/catalog' => [
            'GET' => ['CatalogController', 'read'],
            'POST' => ['CatalogController', 'create'],
            'PUT' => ['CatalogController', 'update'],
            'PATCH' => ['CatalogController', 'changeStatus'],
            'DELETE' => ['CatalogController', 'delete'],
        ],
    ];
}
