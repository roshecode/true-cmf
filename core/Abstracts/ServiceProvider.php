<?php

namespace T\Abstracts;

use T\Services\Container\Box;

abstract class ServiceProvider
{
    /**
     * @var Box $box
     */
    protected $box;

    /**
     * @param Box $box
     * @return ServiceProvider
     */
    public function __register(Box $box) {
        $this->box = $box;
        return $this;
    }

    public function boot() {}
}