<?php
namespace T\Interfaces;

interface Router
{
    public function get($route, $handler);
    
    public function post($route, $handler);
    
    public function put($route, $handler);
    
    public function patch($route, $handler);
    
    public function delete($route, $handler);
    
    public function options($route, $handler);
    
    public function match($routes, $uri, $handler);
}
