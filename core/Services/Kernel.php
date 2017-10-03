<?php

namespace Core\Services;

//use T\Interfaces\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel implements Contracts\Kernel
{
    protected $route;

    public function __construct(Contracts\Route $route)
    {
        $this->route = $route;
    }

    protected function createClass(string $class)
    {
        return new $class;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response A Response instance
     */
    public function handle(
        ServerRequestInterface $request
//        $type = self::MASTER_REQUEST
//        $catch = true
    )
    {
//        $this->box->instance(\T\Interfaces\Request::class, $request);
        $make = $this->route->make($request->getMethod(), $request->getUri()->getPath());
        [$callable, $arguments] = $make;

        if (is_array($callable)) {
            [$class, $method] = [key($callable), current($callable)];
            $content = (new $class)->$method(...$arguments);
        } else {
            $content = call_user_func_array($callable, $arguments);
        }

        return new Response(...
            (is_string($content)
                ? [$content, Response::HTTP_OK, ['content-type' => 'text/html']]
                : [json_encode($content), Response::HTTP_OK, ['content-type' => 'application/json']]
            )
        );
    }

    public function terminate(ServerRequestInterface $request, Response $response)
    {
        // todo
    }
}
