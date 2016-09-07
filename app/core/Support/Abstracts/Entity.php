<?php

namespace Truth\Support\Abstracts;

abstract class Entity
{
    /**
     * @var \Truth\IoC\Box $box
     */
    protected static $box;

    public static function init(&$box) {
        self::$box = $box;
    }
}