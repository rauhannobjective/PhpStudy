<?php

use PhpStudy\Src\Services\CorreiosService;
use PhpStudy\Tests\Mocks\MockCorreiosService;

$c = $GLOBALS['app']->getContainer();

$c->set(CorreiosService::class, function ($c) {
    return new MockCorreiosService();
});

