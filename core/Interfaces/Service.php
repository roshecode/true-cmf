<?php

namespace T\Interfaces;

interface Service
{
    public function register(Box $container);

    public function boot();
}
