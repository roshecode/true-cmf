<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;

class FS extends Facade
{
    const ASSOC        = 'getAssoc';
    const INSERT       = 'insert';
    const INVOLVE      = 'involve';
    const INSERT_ONCE  = 'insertOnce';
    const INVOLVE_ONCE = 'involveOnce';

    protected static function getFacadeAccessor() {
        return 'FS';
    }
}
