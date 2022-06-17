<?php

namespace PhpStudy\Src\Routes;

use PhpStudy\Src\Actions\HomeAction;

$GLOBALS['app']->get('/', HomeAction::class);