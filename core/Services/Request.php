<?php

namespace T\Services;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest {
    public static function capture() {
        static::enableHttpMethodParameterOverride();
        return static::createFromGlobals();
    }

    public function getRequestPath() {
        return strtok(static::getRequestUri(), '?');
    }
}
