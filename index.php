<?php

namespace PhpStudy;

use PhpStudy\Config\App;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';
$settings = require 'config/settings.php';

date_default_timezone_set($settings['settings.timezone']);

new App($settings);

require 'config/dependencies.php';
require 'config/routes.php';

$GLOBALS['app']->run();

