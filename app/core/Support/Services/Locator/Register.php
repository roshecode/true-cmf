<?php

namespace Truth\Support\Services\Locator;

class RegisterThread extends \Thread
{
    /**
     * @var Box
     */
    protected $box;
    protected $abstract;
    protected $concrete;

    public function __construct(&$box, &$abstract, &$concrete)
    {
        $this->box = $box;
        $this->abstract = $abstract;
        $this->concrete = $concrete;
    }
}

class BindRegisterThread extends RegisterThread
{
    public function run() {
        $this->box->bind($this->abstract, $this->concrete);
    }
}

class SingletonRegisterThread extends RegisterThread
{
    public function run() {
        $this->box->singleton($this->abstract, $this->concrete);
    }
}

class MutableRegisterThread extends RegisterThread
{
    public function run() {
        $this->box->mutable($this->abstract, $this->concrete);
    }
}

class RegisterThreads extends \Threaded
{

}