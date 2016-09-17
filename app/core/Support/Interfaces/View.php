<?php

namespace Truth\Support\Interfaces;

interface View
{
    public function display($layout, $data);
    public function render($layout, $data);
}