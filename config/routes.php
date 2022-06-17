<?php

$files = glob(__DIR__ . '/routes/*.php');

foreach ($files as $file) {
    require $file;
}
