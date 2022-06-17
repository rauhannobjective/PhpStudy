<?php

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return [
    'app.name' => $_ENV['APP_NAME'],
    'settings.timezone' => 'America/Sao_Paulo',
    'db.host' => $_ENV['DB_HOST'],
    'db.db_name' => $_ENV['DB_NAME'],
    'db.db_user' => $_ENV['DB_USER'],
    'db.db_pass' => $_ENV['DB_PASS'],
    'db.db_port' => $_ENV['DB_PORT'],
    'db.db_root_pass' => $_ENV['DB_ROOT_PASS'],
    'db.charset' => 'utf8mb4',
];