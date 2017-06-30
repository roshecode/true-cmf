<?php

namespace T\Services;

use T\Interfaces\RequestInterface;
use T\Traits\Servant;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
//use Zend\Diactoros\Request as BaseRequest;
use Zend\Diactoros\ServerRequestFactory;

class Request extends BaseRequest implements RequestInterface
{
    use Servant;

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
