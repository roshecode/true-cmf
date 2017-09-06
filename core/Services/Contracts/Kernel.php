<?php

namespace Core\Services\Contracts;

use Trucode\Services\Interfaces\Response;
//use T\Interfaces\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

interface Kernel
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return Response A Response instance
     */
    public function handle(
        ServerRequestInterface $request
//        $type = self::MASTER_REQUEST,
//        $catch = true
    );
}
