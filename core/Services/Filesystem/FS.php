<?php

namespace T\Services\Filesystem;

use T\Interfaces\FS as FSInterface;
use T\Traits\Service;

class FS extends \Truecode\Filesystem\FS implements FSInterface
{
    use Service;
}
