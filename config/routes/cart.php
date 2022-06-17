<?php

namespace PhpStudy\Config\Routes;

use PhpStudy\Src\Actions\Cart\AddProductAction;
use PhpStudy\Src\Actions\Cart\CalculateTotalValueAction;
use PhpStudy\Src\Actions\Cart\RemoveProductAction;
use PhpStudy\Src\Middlewares\JsonBodyParserMiddleware;
use PhpStudy\Src\Validators\CalculateTotalValueValidator;
use PhpStudy\Src\Validators\AddProductValidator;
use PhpStudy\Src\Validators\RemoveProductValidator;
use Slim\Routing\RouteCollectorProxy;

$GLOBALS['app']->group('/cart', function (RouteCollectorProxy $group) {
    $group->post('/total/{cartId:[0-9]+}', CalculateTotalValueAction::class)->add(CalculateTotalValueValidator::class);
    $group->patch('/add-product/{cartId:[0-9]+}', AddProductAction::class)->add(AddProductValidator::class);
    $group->patch('/remove-product/{cartId:[0-9]+}', RemoveProductAction::class)->add(RemoveProductValidator::class);
})->add(JsonBodyParserMiddleware::class);
