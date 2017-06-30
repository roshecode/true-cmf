<?php

namespace T\Interfaces;

interface ServiceInterface
{
    public function __register(BoxInterface $container);

    public function __boot();
}
