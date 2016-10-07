<?php

namespace T\Support\Abstracts;

use T\Support\Services\Locator\Box;

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