<?php

namespace T\Interfaces;

use T\Interfaces\Response as ResponseInterface;
use T\Interfaces\Request as RequestInterface;

interface Kernel extends Service {
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
