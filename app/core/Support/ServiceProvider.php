<?php

namespace Truth\Support;

interface ServiceProvider
{
    /**
     * @param \Truth\IoC\Box $box
     * @return void
     */
    public static function register(&$box);
}