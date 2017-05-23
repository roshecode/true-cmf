<?php

namespace T\Interfaces;

interface Service
{
    public function __register(Box $container);

    public function __boot();
}
