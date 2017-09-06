<?php

namespace Core\Services;

//use T\Interfaces\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use True\Standards\Container\ContainerAcessibleInterface;
use True\Standards\Container\ContainerAccessTrait;

class Kernel implements Contracts\Kernel
{
    protected $route;

    public function __construct(Contracts\Route $route)
    {
        $this->route = $route;
    }

    /**
     * @param ServerRequestInterface $request
     *
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
        $class = key($make[0]);
        $content = is_array($make[0])
            ? (new $class)->{current($make[0])}(...$make[1])
            : call_user_func_array($make[0], $make[1]);


        return new Response(...(is_string($content)
            ? [$content, Response::HTTP_OK, ['content-type' => 'text/html']]
            : [json_encode($content), Response::HTTP_OK, ['content-type' => 'application/json']])
        );
    }

    public function terminate(ServerRequestInterface $request, Response $response)
    {
        // todo
    }
}
