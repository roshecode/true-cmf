<?php

namespace T\Services;

use T\Interfaces\Kernel as KernelInterface;
use T\Interfaces\Route as RouteInteface;
use T\Interfaces\Request as RequestInterface;
use T\Traits\Service;

class Kernel implements KernelInterface
{
    use Service;

    /**
     * @param RequestInterface $request
     * @return Response A Response instance
     */
    public function handle(
        RequestInterface $request
//        $type = self::MASTER_REQUEST
//        $catch = true
    ) {
//        $this->box->instance(\T\Interfaces\Request::class, $request);
        $make = $this->box->make(RouteInteface::class)->make($request->getMethod(), $request->getRequestPath());
        $content = is_array($make[0])
            ? $this->box->make(key($make[0]))->{current($make[0])}(...$make[1])
            : call_user_func_array($make[0], $make[1]);
        return $this->box->make(Response::class, is_string($content)
            ? [$content, Response::HTTP_OK, ['content-type' => 'text/html']]
            : [json_encode($content), Response::HTTP_OK, ['content-type' => 'application/json']]
        );
    }

    public function terminate(RequestInterface $request, Response $response) {
        // todo
    }
}
