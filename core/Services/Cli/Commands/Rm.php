<?php

namespace T\Services\Cli\Commands;

use T\Services\Cli\Abstracts\Command;

class Rm extends Command
{
    protected function rm($settingsKey, $instanceName, $params) {
        $className = ucfirst($params[0]);
        $directory = $this->getDirectory($settingsKey);
        $file = $directory . '/' . ucfirst($className) . '.' . Make::CLASS_FILE_EXTENSION;
        if (file_exists($file)) {
            if (@unlink($file)) {
                echo "{$instanceName} {$className} was successfully removed from '{$directory}'.";
            } else echo "Cannot delete {$instanceName} {$className} from '{$directory}'!";
        } else echo "{$instanceName} {$className} does not exists in '{$directory}'";
    }

    public function facade($params) {
        $this->rm('facades', 'facade', $params);
    }

    public function iface($params) {
        $this->rm('interfaces', 'interface', $params);
    }
}