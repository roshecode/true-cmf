<?php

namespace Truth\Support\Abstracts;

abstract class ServiceProvider
{
    /**
     * @var \Truth\Support\Services\Locator\Box $box
     */
    protected static $box;

    /**
     * @param \Truth\Support\Services\Locator\Box $box
     */
    public static function register(&$box) {
        self::$box = $box;
    }
}