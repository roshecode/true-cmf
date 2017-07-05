<?php

namespace T\Interfaces;

use T\Interfaces\ResponseInterface;
//use T\Interfaces\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

interface KernelInterface extends ServiceInterface {
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface A Response instance
     */
    public function handle(
        ServerRequestInterface $request
//        $type = self::MASTER_REQUEST,
//        $catch = true
    );
}
