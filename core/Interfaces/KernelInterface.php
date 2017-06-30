<?php

namespace T\Interfaces;

use T\Interfaces\ResponseInterface;
use T\Interfaces\RequestInterface;

interface KernelInterface extends ServiceInterface {
    /**
     * @param RequestInterface $request
     * @return ResponseInterface A Response instance
     */
    public function handle(
        RequestInterface $request
//        $type = self::MASTER_REQUEST,
//        $catch = true
    );
}
