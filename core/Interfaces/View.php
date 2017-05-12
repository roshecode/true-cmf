<?php
namespace T\Interfaces;

interface View extends Service
{
    public function display($layout, $data);
    
    public function render($layout, $data);
}
