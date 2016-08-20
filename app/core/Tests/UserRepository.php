<?php

namespace Truth;

class UserRepository {
    protected $something;
    protected $somethingElse;
    public function __construct(SomeInterface $something, SomethingElse $somethingElse) {
        $this->something = $something;
        $this->something = $somethingElse;
    }
    public function test1() {}
    public function test2() {}
    public function test3() {}
}
interface SomeInterface {}
class Something implements SomeInterface{}
class SomethingElse {}
