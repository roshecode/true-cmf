<?php

namespace Truth\Exceptions;

class EnvisageException extends \Exception
{
    protected $data;

    public function __construct($message, $data, $code, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getFirstTrace() {
        $out = $this->getTrace();
        $out = $out[count($out) - 1];
        array_pop($out);
        $out['code'] = $this->code;
//        $out['filename'] = ltrim(strrchr($out['file'], DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR);
        $out['filename'] = ltrim(strrchr($out['file'], DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR);
        return array_merge($out, $this->data);
    }

//    public function __toString() {
//        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
//    }
}