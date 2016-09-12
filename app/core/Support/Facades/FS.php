<?php

namespace Truth\Support\Facades;

use Truth\Support\Abstracts\Facade;
use Truth\Support\Services\FileSystem\FS as File;

class FS extends Facade
{
    const TAKE         = File::TAKE;
    const READ         = File::READ;
    const ASSOC        = File::ASSOC;
    const INSERT       = File::INSERT;
    const INVOLVE      = File::INVOLVE;
    const INSERT_ONCE  = File::INSERT_ONCE;
    const INVOLVE_ONCE = File::INVOLVE_ONCE;

    protected static function getFacadeAccessor() {
        return 'FS';
    }
}
