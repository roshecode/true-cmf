<?php

namespace Core\Services\Enums\App;

abstract class Mode
{
    const __default = self::DEFAULT;

    const DEFAULT     = 'default';
    const DEVELOPMENT = 'development';
    const PRODUCTION  = 'production';
    const MAINTENANCE = 'maintenance';
}
