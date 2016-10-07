<?php

namespace T\Exceptions;

class EnvisageError extends EnvisageException
{
    public function __construct($errCode, $errStr, $errFile, $errLine)
    {
        parent::__construct($errStr, $errCode, null);
//        $this->data['code'] = $errCode;
//        $this->data['file'] = $errFile;
//        $this->data['line'] = $errLine;
        $this->message = $this->parseStr(
            '{{ filename }}({{ line }}): ' .
            'value "{{ 0 }}" passed to "{{ param }}" parameter in "{{ function }}" function at position {{ position }} expected to be {{ expected }}, type {{ given }} given.' .
            ' Code {{ code }}',
            $this->data, '<mark>', '</mark>');
    }
}