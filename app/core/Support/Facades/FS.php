<?php

namespace T\Support\Facades;

use T\Support\Abstracts\Facade;
use T\Support\Services\FileSystem\FS as _FS;

class FS extends Facade
{
    const TAKE         = _FS::TAKE;
    const READ         = _FS::READ;
    const ASSOC        = _FS::ASSOC;
    const INSERT       = _FS::INSERT;
    const INVOLVE      = _FS::INVOLVE;
    const INSERT_ONCE  = _FS::INSERT_ONCE;
    const INVOLVE_ONCE = _FS::INVOLVE_ONCE;

    protected static function getFacadeAccessor() {
        return 'FS';
    }
}
