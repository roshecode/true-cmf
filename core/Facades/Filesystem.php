<?php

namespace T\Facades;

use T\Abstracts\Facade;
use T\Services\Filesystem\Filesystem as FS;

class Filesystem extends Facade
{
    const TAKE         = FS::TAKE;
    const READ         = FS::READ;
    const ASSOC        = FS::ASSOC;
    const INSERT       = FS::INSERT;
    const INVOLVE      = FS::INVOLVE;
    const INSERT_ONCE  = FS::INSERT_ONCE;
    const INVOLVE_ONCE = FS::INVOLVE_ONCE;

    protected static function getFacadeAccessor() {
        return \T\Interfaces\Filesystem::class;
    }
}
