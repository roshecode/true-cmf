<?php

namespace T\Services\Cli;

class Terminal
{
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function terminate($args) {
        unset($args[0]);
        $params = explode(':', $args[1]);
        $command = __NAMESPACE__ . '\\Commands\\' . ucfirst($params[0]);
        $subCommand = $params[1];
        array_shift($args);
        (new $command($this->settings))->$subCommand($args);
    }
}
