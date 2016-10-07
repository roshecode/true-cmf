<?php

namespace T\Support\Services\Cli\Commands;

use T\Support\Services\Cli\Abstracts\Command;

class Make extends Command
{
    const TEMPLATE_EXTENSION    = 'tpl';
    const CLASS_FILE_EXTENSION  = 'php';

    protected function make($settingsKey, $instanceName, $params) {
        $instanceName = ucfirst($instanceName);
        $template = __DIR__ . '/templates/' . $instanceName . '.' . self::TEMPLATE_EXTENSION;
        $settings = $this->settings[$settingsKey];
        $className = ucfirst($params[0]);
        $file = $settings['directory'] . '/' . $className . '.' . self::CLASS_FILE_EXTENSION;
        if (! file_exists($file)) {
            if (file_put_contents($file, $this->parse($template, array_merge($settings, [
                'class_name' => $className,
                'abstract_class_name' => empty($settings['use']) ?: (new \ReflectionClass($settings['use']))->getShortName()
            ])))) {
                echo "{$instanceName} {$className} was successfully created in '{$template}'";
            } else echo 'Something went wrong when was trying to write the file';
        } else echo "{$instanceName} {$className} file is already exists!";
    }

    public function facade($params) {
        $this->make('facades', 'facade', $params);
    }

    public function iface($params) {
        $this->make('interfaces', 'interface', $params);
    }
}
