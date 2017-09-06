<?php

class ValidationRule
{
    protected $validate;

    protected $message;

    /**
     * Validation constructor.
     *
     * @param array|callable $validate
     * @param string $message
     */
    public function __construct($validate, $message)
    {
        $this->validate = $validate;
        $this->message = $message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function applyTo(string $field)
    {
        return $this;
    }

    public function validate($value)
    {
        return $this->{'validate'}($value);
    }
}
