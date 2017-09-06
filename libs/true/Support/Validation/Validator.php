<?php

class Validator
{
    public function bag(array $validations, $stopOnFirstError = false)
    {
        return new ValidationRule($validations, '');
    }

    public function equal($value)
    {

    }

    public function max($value)
    {

    }

    public function number()
    {
        return new ValidationRule(function () {
        }, 'The :field must be a number');
    }

    public function range(array $from, array $to)
    {
        return new ValidationRule(function () {
        }, 'The :field must be in rage from :from to :to');
    }

    public function required($required = true)
    {
        return new ValidationRule(function ($value) use (&$required) {
            return $required
                ? is_array($value)
                    ? ! empty($value)
                    : is_object($value)
                        ? ! empty(get_object_vars($value))
                        : ($value !== null) && trim((string) $value) !== ''
                : true;
        }, 'The :field is required');
    }

    public function size($value)
    {

    }

    public function string()
    {
        return new ValidationRule(function ($value) {
            return is_string($value);
        }, 'The value must be a string');
    }

    public function unique($name)
    {
        //
    }
}
