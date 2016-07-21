<?php

namespace Extension;


interface Module
{
    public function install();
    public function init();
    public function delete();
}