<?php

namespace T\Services;

use T\Interfaces\Route;
use T\Services\Request;
use T\Services\Response;
use T\Traits\Service;
use T\Interfaces\Kernel as KernelInterface;

class Kernel implements KernelInterface
{
    use Service;

    /**
     * @param Request $request
     * @return Response A Response instance
     */
    public function handle(
        Request $request
//        $type = self::MASTER_REQUEST
//        $catch = true
    ) {
        $make = $this->box->make(Route::class)->make($request->getMethod(), $request->getRequestPath());
        $content = is_array($make[0])
            ? $this->box->make(key($make[0]))->{current($make[0])}(...$make[1])
            : call_user_func_array($make[0], $make[1]);
        return $this->box->make(Response::class, is_string($content)
            ? [$content, Response::HTTP_OK, ['content-type' => 'text/html']]
            : [json_encode($content), Response::HTTP_OK, ['content-type' => 'application/json']]
        );
    }

    public function terminate(Request $request, Response $response) {
        // todo
    }
}
