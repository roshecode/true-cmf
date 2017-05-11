<?php

namespace T\Services\Http;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest {
    public static function capture() {
        return self::createFromGlobals();
    }
}
