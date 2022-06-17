<?php

use PhpStudy\Config\App;

require '././vendor/autoload.php';

$settings = require '././config/settings.php';

\ini_set('date.timezone', $settings['settings.timezone']);

new App($settings);

require '././config/dependencies.php';
require 'test-dependencies.php';
require '././config/routes.php';