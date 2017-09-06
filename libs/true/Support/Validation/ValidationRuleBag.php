<?php

class ValidationRuleBag extends ValidationRule
{
    public function validate($value)
    {
        return $this->{'validate'}($value);
    }
}
