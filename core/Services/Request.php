<?php

namespace T\Services;

use Symfony\Component\HttpFoundation\Request as BaseRequest;
use T\Traits\Service;

class Request extends BaseRequest implements \T\Interfaces\Request
{
    use Service;

//    public function __boot()
//    {
//        static::enableHttpMethodParameterOverride();
//        $this->box->instance(\T\Interfaces\Request::class, static::createFromGlobals()); //TODO: improve
//    }

    public static function capture() {
        static::enableHttpMethodParameterOverride();
        return static::createFromGlobals();
    }

    public function getRequestPath() {
        return strtok(static::getRequestUri(), '?');
    }
}
