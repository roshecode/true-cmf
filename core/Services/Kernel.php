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
        $response = $this->box[Route::class]->make($request->getMethod(), $request->getRequestPath());
//        return new Response(
//            $response[0]($response[1]),
//            Response::HTTP_OK,
//            ['content-type' => 'text/html']
////            ['content-type' => 'application/json']
//        );
        return $this->box->make(Response::class, [
            $response[0]($response[1]),
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        ]);
    }

    public function terminate(Request $request, Response $response) {
        // todo
    }
}
