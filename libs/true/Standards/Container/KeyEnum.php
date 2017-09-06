<?php

namespace True\Standards\Container;

/**
 * Enumeration of available keys in config
 */
abstract class KeyEnum
{
    const __default = self::Concrete;

    const Concrete = 0;
    const Arguments = 1;
    const Alias = 2;
}
