<?php

namespace Truth\Support\Services\Routing;

class RouteRepository
{
    const REGEX = 0;
    const NEXT  = 1;

    protected $data;
    protected $pointer;
    protected $next;
    protected $separator;
    protected $placeholder;

    public function __construct(array $data = [], $separator = '/', $placeholder = ':')
    {
        $this->data = $data;
        $this->separator = $separator;
        $this->placeholder = $placeholder;
    }

    public function set($query, $value) {
        $this->pointer = &$this->data;
//        $this->next = [];
        preg_replace_callback('~' . $this->placeholder . '?[\w-]+(?!/)?~i',
            function($matches) {
//                pr($matches[0] . ': ', $this->data);
                if ($matches[0][0] === ':') {
                    $cur = &$this->pointer[':'][$matches[0]];
                    $cur = [self::REGEX => 'regex_expression'];
                    $this->next = &$cur[self::NEXT];
                } else {
//                    unset($this->next);
//                    $this->pointer[$matches[0]] = &$this->next;
                    $this->next = &$this->pointer[$matches[0]];
                }
                $this->pointer = &$this->next;
        }, $query);
        $this->pointer = $value;
    }

    public function make($query) {

    }

    public function getData() {
        return $this->data;
    }
}
