<?php

namespace T\Support\Abstracts;

class ProxyMake
{
    protected $proxyClosure;

    public function __construct($proxyClosure)
    {
        $this->proxyClosure = $proxyClosure;
    }

    public function invoke() {
        $this->proxyClosure->make();
    }
}

class ProxyLoad extends ProxyMake
{
    public function invoke() {
        $this->proxyClosure->load();
        parent::invoke();
        $this->proxyClosure->loadThenMake = new ProxyMake($this->proxyClosure);
    }
}

abstract class ProxyClosure3
{
    public $loadThenMake;

    public function __construct()
    {
        $this->loadThenMake = new ProxyLoad($this);
    }

    abstract public function load();

    abstract public function make();

    public function invoke() {
        $this->loadThenMake->invoke();
    }
}

abstract class ProxyClosure
{
    protected $load = false;

    abstract public function load();

    abstract public function make();

    public function invoke() {
        if ($this->load) $this->make(); else {
            $this->load();
            $this->load = true;
            $this->make();
        }
    }
}

abstract class ProxyClosure2
{
    protected $make = '__load';

    abstract public function load();

    abstract public function make();

    protected function __load() {
        $this->load();
        $this->make = 'make';
        $this->make();
    }

    public function invoke() {
        $make = $this->make;
        $this->$make();
    }
}