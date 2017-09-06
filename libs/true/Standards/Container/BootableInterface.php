<?php

namespace True\Standards\Container;

/**
 * Representation of a bootable instance
 */
interface BootableInterface
{
    /**
     * Executes after construct
     */
    public function __boot();
}
