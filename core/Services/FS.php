<?php

namespace T\Services;

use T\Interfaces\FSInterface;
use T\Traits\Servant;

class FS extends \Truecode\Filesystem\FS implements FSInterface
{
    use Servant;
}
