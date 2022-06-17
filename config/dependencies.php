<?php

use PhpStudy\Src\Services\CorreiosService;

$c = $GLOBALS['app']->getContainer();

$c->set(PDO::class, function ($c) {
    $host = $c->get('db.host');
    $dbname = $c->get('db.db_name');
    $charset = $c->get('db.charset');
    $dbUser = $c->get('db.db_user');
    $dbPass = $c->get('db.db_pass');

    return new PDO(
        "mysql:host=$host;dbname=$dbname;charset=$charset",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
});

$c->set(CorreiosService::class, function ($c) {
    return new CorreiosService();
});
