<?php

namespace T\Interfaces\Foundation\Http;

use T\Services\Http\Response;
use T\Services\Http\Request;

interface Kernel {
    // ...

    /**
     * @param Request $request
     * @return Response A Response instance
     */
    public function handle(
        Request $request
//        $type = self::MASTER_REQUEST,
//        $catch = true
    );
}
