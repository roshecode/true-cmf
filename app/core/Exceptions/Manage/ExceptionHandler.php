<?php

namespace Truth\Exceptions\Manage;

use Truth\Exceptions\EnvisageException;

class ExceptionHandler
{
    public function __invoke(EnvisageException $exception)
    {
        $trace = $exception->getFirstTrace();
        $errorLine = $trace['line'];
        $height = 7;

        $currentLine = 0;
        $startLine = $errorLine > $height ? $errorLine - $height : $errorLine;
        $endLine = $errorLine + 1 + $height;

        $data = [];
        $data['title'] = 'Error!';
        $data['code'] = '<ol start="' . $startLine . '">';
        $fh = fopen($trace['file'],'r');
        while ($line = fgets($fh)) {
            ++$currentLine;
            if ($currentLine == $errorLine) {
                $data['code'] .= '<li class="active" data-line=">' . $errorLine . '">' . $line . '</li>';
                continue;
            }
            if ($currentLine >= $startLine) {
                $data['code'] .= '<li>' . $line . '</li>';
            }
            if ($currentLine == $endLine) {
                break;
            }
        }
        fclose($fh);
        $data['code'] .= '</ol>';
        $data['exception'] = 'Truth\\Exceptions\\<mark>InvalidArgumentException</mark>';
        $data['message'] = static::parseStr($exception->getMessage(), $trace, '<mark>', '</mark>');
        $data['trace'] = $exception->getTraceAsString();
        $data['server'] = '<ul>';
        foreach ($_SERVER as $key => $value) {
            $data['server'] .= '<li><strong>' . $key . '</strong><span>' . $value . '</span></li>';
        }
        $data['server'] .= '</ul>';
        $data['dir'] = str_replace(getcwd(), '', __DIR__) . '/page';
        $data['script'] = str_replace(getcwd(), '', __DIR__) . '/../../External/ace-builds/src-noconflict/ace.js';
        $data['style'] = str_replace(getcwd(), '', __DIR__) . '/page/style.css';
        echo static::parseStr(file_get_contents(__DIR__ . '/page/template.html'), $data);
    }

    protected static function parseStr($str, $data, $wrapLeft = '', $wrapRight = '') {
        return preg_replace_callback('|{{\s*\w+\s*}}|U', function($matches) use($data, $wrapLeft, $wrapRight) {
            $key = trim($matches[0], '{ }');
            return isset($data[$key]) ? $wrapLeft . $data[$key] . $wrapRight : '';
        },$str);
    }
}