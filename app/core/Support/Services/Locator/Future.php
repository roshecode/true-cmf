<?php

namespace Truth\Support\Services\Locator;

use Thread;
use Threaded;
use Closure;

class Future extends Thread {
    private function __construct(Closure $closure, array $args = []) {
        $this->closure = $closure;
        $this->args    = $args;
    }
    public function run() {
        $this->synchronized(function() {
            $this->result =
                (array) $this->closure(...$this->args);
            $this->notify();
        });
    }
    public function getResult() {
        return $this->synchronized(function(){
            while (!$this->result)
                $this->wait();
            return $this->result;
        });
    }

    public static function of(Closure $closure, array $args = []) {
        $future =
            new self($closure, $args);
        $future->start();
        return $future;
    }

    protected $owner;
    protected $closure;
    protected $args;
    protected $result;
}
