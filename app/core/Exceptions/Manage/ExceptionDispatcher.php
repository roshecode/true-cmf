<?php

namespace Truth\Exceptions\Manage;

class ExceptionDispatcher
{
    protected $stack;

    public function register(ExceptionHandler $handler) {
        $this->stack[] = $handler;
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler'], E_ALL | E_STRICT);
        register_shutdown_function([$this, "fatalHandler"]);
    }

    public function exceptionHandler(\Exception $exception) {
        foreach ($this->stack as $handler) {
//            echo $handler($exception);
            $handler($exception);
        }
    }

    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
        if (!(error_reporting() & $errno)) {
            dd($errstr);
            // Этот код ошибки не входит в error_reporting
            return;
        }
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public function fatalHandler() {
        $errfile = "unknown file";
        $errstr  = "shutdown";
        $errno   = E_CORE_ERROR;
        $errline = 0;

        $error = error_get_last();

        if( $error !== NULL) {
            $errno   = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr  = $error["message"];

//            error_mail(format_error( $errno, $errstr, $errfile, $errline));
//            dd(format_error( $errno, $errstr, $errfile, $errline));
            dd($error);
        }
    }
}
