<?php

require_once __DIR__ . '/System/helpers.php';
require_once __DIR__ . '/System/Config.php';
require_once __DIR__ . '/System/Loader.php';

use True\System\Config;
use True\System\Loader;

Loader::register('True', '/core');
Loader::register('Logic', '/app/logic');
Config::load('core/config.php');
