<?php

$routes = [
    'GET' => [
        '/api/test' => ['IndexController', 'hello'],
        '/api/catalog' => ['CatalogController', 'read'],
    ],
    'POST' => [
        '/api/catalog' => ['CatalogController', 'create'],
    ],
    'PUT' => [
        '/api/catalog' => ['CatalogController', 'update'],
    ],
    'PATCH' => [
        '/api/catalog' => ['CatalogController', 'changeStatus'],
    ],
    'DELETE' => [
        '/api/catalog' => ['CatalogController', 'delete'],
    ],
];