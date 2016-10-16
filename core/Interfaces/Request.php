<?php
namespace T\Interfaces;

interface Request
{
    public function domain();
    
    public function method();
    
    public function path();
}
