<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/System/Autoloader.php';

Autoloader::register('True', getcwd().'/core');
Autoloader::register('Backend', getcwd().'/back/Controllers');
