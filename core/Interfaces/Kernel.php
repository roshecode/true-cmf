<?php

namespace T\Interfaces;

use T\Services\Response;
use T\Services\Request;

interface Kernel extends Service {
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
