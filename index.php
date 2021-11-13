<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/routes.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');

navigate($routes);