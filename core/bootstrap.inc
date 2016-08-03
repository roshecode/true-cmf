<?php

require_once __DIR__ . '/System/helpers.php';
require_once __DIR__ . '/System/Loader.php';

use True\System\Loader;
use True\System\Config;

Loader::register('True', '/core');
Loader::register('Logic', '/app/logic');

Config::load('core/config.php');
