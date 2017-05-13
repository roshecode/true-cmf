<?php

namespace T\Services\Foundation\Http;

use T\Interfaces\Route;
use T\Services\Http\Request;
use T\Services\Http\Response;
use T\Traits\Service;
use T\Interfaces\Foundation\Http\Kernel as KernelInterface;

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
        d([
            $request->getMethod(),
            $request->getRequestPath()
        ]);
        $response = $this->box[Route::class]->make($request->getMethod(), $request->getRequestPath());
        return new Response(
            $response[0]($response[1]),
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    public function terminate(Request $request, Response $response) {
        // todo
    }
}
