<?php

namespace T\Services\Cli\Abstracts;

use T\Services\Filesystem\Exceptions\FileNotFoundException;

class Command
{
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function render($filePath, $vars) {
        if (is_file($filePath)) {
            $template = file_get_contents($filePath);
            return preg_replace_callback('@_(\w+)_@i', function($matches) use(&$vars) {
                return $vars[$matches[1]];
            }, $template);
        } else throw new FileNotFoundException('Terminal template not found');
    }

    public function getDirectory($which) {
        $root = &$this->settings['root'];
        $namespace = &$this->settings[$which]['namespace'];
        return preg_replace('~' . $root['namespace'] . '~', $root['directory'], str_replace('\\', '/', $namespace), 1);
    }
}
