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
//        $type = self::MASTER_REQUEST,
//        $catch = true
    ) {
        $make = $this->box->make(Route::class)->make($request->getMethod(), $request->getRequestPath());
        if (is_array($make[0])) {
            $class = key($make[0]);
            $method = current($make[0]);
            $content = $this->box->make($class)->$method(...$make[1]);
        } else {
            $content = call_user_func_array($make[0], $make[1]);
        }
        return $this->box->make(Response::class, [
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
//            ['content-type' => 'application/json']
        ]);
    }

    public function terminate(Request $request, Response $response) {
        // todo
    }
}
