<?php

namespace T\Services\Foundation\Http;

use T\Abstracts\ServiceProvider;
use T\Services\Http\Request;
use T\Services\Http\Response;
use T\Interfaces\Foundation\Http\Kernel as KernelInterface;

class Kernel extends ServiceProvider implements KernelInterface {

    /**
     * @param Request $request
     * @return Response A Response instance
     */
    public function handle(
        Request $request
//        $type = self::MASTER_REQUEST,
//        $catch = true
    ) {
        return new Response(
            $this->box['Route']->make($request->getMethod(), $request->getRequestUri())[0](),
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    public function terminate(Request $request, Response $response) {
        // todo
    }
}
