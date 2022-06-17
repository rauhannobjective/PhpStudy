<?php

$settings = require 'config/settings.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/data/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/data/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $settings['db.host'],
            'name' => $settings['db.db_name'],
            'user' => $settings['db.db_user'],
            'pass' => $settings['db.db_pass'],
            'port' => $settings['db.db_port'],
            'charset' => $settings['db.charset'],
            'connection' => new PDO('mysql:host=' . $settings['db.host'] . ';dbname=' . $settings['db.db_name'],
                $settings['db.db_user'], $settings['db.db_pass'])
        ]
    ],
    'version_order' => 'creation'
];
