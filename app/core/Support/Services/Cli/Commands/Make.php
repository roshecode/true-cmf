<?php

namespace T\Support\Services\Cli\Commands;

use T\Support\Services\Cli\Abstracts\Command;

class Make extends Command
{
    const TEMPLATE_EXTENSION    = 'tpl';
    const CLASS_FILE_EXTENSION  = 'php';

    protected function make($settingsKey, $instanceName, $params) {
//        $instanceName = ucfirst($instanceName);
        $className = ucfirst($params[0]);
        $directory = $this->getDirectory($settingsKey);
        $template = __DIR__ . '/templates/' . $instanceName . '.' . self::TEMPLATE_EXTENSION;
        $settings = $this->settings[$settingsKey];
        $file = $directory . '/' . $className . '.' . self::CLASS_FILE_EXTENSION;
        if (! file_exists($file)) {
            if (file_put_contents($file, $this->render($template, array_merge($settings, [
                'class_name' => $className,
                'abstract_class_name' => empty($settings['extend']) ?:
                    (new \ReflectionClass($settings['extend']))->getShortName()
            ])))) {
                echo "{$instanceName} {$className} was successfully created in '{$directory}'";
            } else echo 'Something went wrong when was trying to write the file';
        } else echo "{$instanceName} {$className} file is already exists in '{$directory}'!";
    }

    public function facade($params) {
        $this->make('facades', 'facade', $params);
    }

    public function iface($params) {
        $this->make('interfaces', 'interface', $params);
    }
}
