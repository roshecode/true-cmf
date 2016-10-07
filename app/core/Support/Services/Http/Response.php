<?php

namespace T\Support\Services\Http;

class HttpStatus
{
    const OK = 200;
    const NOT_FOUND = 404;
}

class Response
{
    public $headers;
    protected $content;
    protected $version;
    protected $statusCode;
    protected $statusMsg;
    protected $charset;

    public function __construct($content = '', $status = HttpStatus::OK, $headers = [])
    {
//        $this->setContent($content);
//        $this->setStatusCode($status);
//        $this->setProtocolVersion('1.0');
        $this->statusCode = $status;
        $this->version = '1.0';
    }

    public function setStatusCode($code) {
        http_response_code($code);
        return $this;
    }

    public static function create($content = '', $status = 200, $headers = [])
    {
        return new static($content, $status, $headers);
    }

    public function sendHeaders()
    {
        // headers have already been sent by the developer
        if (! headers_sent()) {
//            if (!$this->headers->has('Date')) {
//                $this->setDate(\DateTime::createFromFormat('U', time()));
//            }
            // headers
//            foreach ($this->headers->allPreserveCase() as $name => $values) {
//                foreach ($values as $value) {
//                    header($name.': '.$value, false, $this->statusCode);
//                }
//            }
            // status
//            header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText), true, $this->statusCode);
            header('HTTP/' . $this->version . ' ' . $this->statusCode . ' ' . $this->statusMsg, true, $this->statusCode);
            // cookies
//            foreach ($this->headers->getCookies() as $cookie) {
//                if ($cookie->isRaw()) {
//                    setrawcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(),
//                        $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
//                } else {
//                    setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(),
//                        $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
//                }
//            }
        }
        return $this;
    }

    public function sendContent()
    {
        echo $this->content;
        return $this;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif ('cli' !== PHP_SAPI) {
            static::closeOutputBuffers(0, true);
        }
        return $this;
    }

    /**
     * Cleans or flushes output buffers up to target level.
     *
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     *
     * @param int  $targetLevel The target output buffering level
     * @param bool $flush       Whether to flush or clean the buffers
     */
    public static function closeOutputBuffers($targetLevel, $flush)
    {
        $status = ob_get_status(true);
        $level = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE') ?
            PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE) : -1;
        while ($level-- > $targetLevel && ($s = $status[$level]) &&
            (!isset($s['del']) ? !isset($s['flags']) || $flags === ($s['flags'] & $flags) : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }
}