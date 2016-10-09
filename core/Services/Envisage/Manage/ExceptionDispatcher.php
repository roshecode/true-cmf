<?php

namespace T\Exceptions\Manage;

use T\Exceptions\EnvisageError;

class ExceptionDispatcher
{
    protected $stack;

    public function register(ExceptionHandler $handler) {
        $this->stack[] = $handler;
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler'], E_ALL | E_STRICT);
//        register_shutdown_function([$this, "fatalHandler"]);
    }

    public function exceptionHandler(\Exception $exception) {
        foreach ($this->stack as $handler) {
//            echo $handler($exception);
            $handler($exception);
        }
    }

    public function errorHandler($errCode, $errStr, $errFile, $errLine, $errContext) {
        if (!(error_reporting() & $errCode)) {
            // Этот код ошибки не входит в error_reporting
            return;
        }
        throw new EnvisageError($errCode, $errStr, $errFile, $errLine, $errContext);
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
            foreach ($this->stack as $handler) {
//            echo $handler($exception);
                $handler($error);
            }
        }
        dd($error);
    }
}
