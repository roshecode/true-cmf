<?php

require_once __DIR__ . '/app/vendor/autoload.php';

$settings = parse_ini_file(__DIR__ . '/app/configuration/namespaces.ini', true);

(new \T\Support\Services\Cli\Terminal($settings))->terminate($argv);
