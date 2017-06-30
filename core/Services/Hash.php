<?php

namespace T\Services;

use T\Interfaces\HashInterface;
use T\Traits\Servant;

class Hash implements HashInterface
{
    use Servant;

    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Make a hash
     *
     * @param string $str
     * @return string
     */
    public function make(string $str)
    {
//        return \Sodium\crypto_pwhash_str(
//            $str,
//            \Sodium\CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
//            \Sodium\CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
//        );
        return password_hash($str, PASSWORD_BCRYPT, $this->options);
    }

    /**
     * Verify that a given plain-text string corresponds to a given hash
     *
     * @param string $str
     * @param string $hash
     * @return bool
     */
    public function check(string $str, string $hash)
    {
//        $checked = \Sodium\crypto_pwhash_str_verify($hash, $str);
//        // wipe the plaintext password from memory
//        \Sodium\memzero($password);
//        return $checked;
        return password_verify($str, $hash);
    }
}
