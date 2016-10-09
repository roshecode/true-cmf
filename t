<?php

require_once __DIR__ . '/vendor/autoload.php';

$settings = parse_ini_file(__DIR__ . '/config/app.ini', true);

(new \T\Services\Cli\Terminal($settings))->terminate($argv);
