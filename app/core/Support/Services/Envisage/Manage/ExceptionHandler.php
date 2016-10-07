<?php

namespace T\Exceptions\Manage;

use T\Exceptions\EnvisageException;

class ExceptionHandler
{
    public function __invoke(EnvisageException $exception)
    {
        $trace = $exception->getData();
//        $trace = $exception->getTrace();
//        $trace = $trace[count($trace) - 1];
        $errorLine = $trace['line'];
        $height = 7;

        $currentLine = 0;
        $startLine = $errorLine > $height ? $errorLine - $height : 1;
        $endLine = $errorLine + 0 + $height;

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
        $exceptionName = ltrim(strrchr($trace['exception'], '\\'), '\\');
        $data['exception'] = str_replace($exceptionName, '<mark>' . $exceptionName . '</mark>', $trace['exception']);
        $data['message'] = $exception->getMessage();
        $data['trace'] = $exception->getTraceAsString();
        $data['server'] = '<ul>';
        foreach ($_SERVER as $key => $value) {
            $data['server'] .= '<li><strong>' . $key . '</strong><span>' . $value . '</span></li>';
        }
        $data['server'] .= '</ul>';
        $data['dir'] = str_replace(getcwd(), '', __DIR__) . '/page';
        $data['script'] = str_replace(getcwd(), '', __DIR__) . '/../../External/ace-builds/src-noconflict/ace.js';
        echo $exception->parseStr(file_get_contents(__DIR__ . '/page/template.html'), $data);
    }
}