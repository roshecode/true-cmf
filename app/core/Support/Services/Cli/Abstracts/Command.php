<?php

namespace T\Support\Services\Cli\Abstracts;

use T\Support\Services\FileSystem\Exceptions\FileNotFoundException;

class Command
{
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function parse($filePath, $vars) {
        if (is_file($filePath)) {
            $template = file_get_contents($filePath);
            return preg_replace_callback('@_(\w+)_@i', function($matches) use(&$vars) {
                return $vars[$matches[1]];
            }, $template);
        } else throw new FileNotFoundException('Terminal template not found');
    }
}
