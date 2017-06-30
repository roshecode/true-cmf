<?php

namespace T\Interfaces;

interface HashInterface extends ServiceInterface {
    /**
     * Make a hash
     *
     * @param string $str
     * @return string
     */
    public function make(string $str);

    /**
     * Verify that a given plain-text string corresponds to a given hash
     *
     * @param string $str
     * @param string $hash
     * @return bool
     */
    public function check(string $str, string $hash);
}
