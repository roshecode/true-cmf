<?php

namespace Truth\Support\Services\Routing;

class RouteRepository
{
    const REGEX = 0;
    const NEXT  = 1;
    const MAKE  = '+';

    protected $data;
    protected $pointer;
    protected $next;
    protected $params;
    protected $separator;
    protected $placeholder;
    protected $filters = [
        'letters' => '^[a-zA-Z]+$',
        'int' => '^-?\d+$',
        'uint' => '^\d+$',
    ];

    public function __construct(array $data = [], $separator = '/', $placeholder = ':')
    {
        $this->data = $data;
        $this->separator = $separator;
        $this->placeholder = $placeholder;
    }

    protected function pass($query, $callback) {
        $this->pointer = &$this->data;
//        preg_replace_callback('~' . $this->placeholder . '?[\w-]+(?!/)?~i', $callback, $query);
        preg_replace_callback('~' . $this->placeholder . '?[^/]+(?!/)?~i', $callback, $query);
    }

    public function set($query, $callback) {
        $this->pass($query, function($matches) {
            if ($matches[0][0] === ':') {
                $parts_preg = explode(':', $matches[0], 3);
                $parts_fltr = explode('|', $parts_preg[1], 2);
                if (isset($parts_fltr[1])) {
                    $parts_preg[1] = $parts_fltr[0];
                    $regex = $this->filters[$parts_fltr[1]];
                } elseif (isset($parts_preg[2])) {
                    $regex = $parts_preg[2];
                } else {
                    $regex = '';
                }
                $cur = &$this->pointer[':'][$parts_preg[1]];
                $cur = [self::REGEX => '~' . $regex . '~'];
                $this->next = &$cur[self::NEXT];
            } else {
                $this->next = &$this->pointer[$matches[0]];
            }
            $this->pointer = &$this->next;
        });
        $this->pointer[self::MAKE] = $callback;
    }

    public function make($query) {
        $this->pass($query, function($matches) {
            if (isset($this->pointer[$matches[0]])) {
                $this->next = &$this->pointer[$matches[0]];
            } else {
                foreach ($this->pointer[':'] as $placeholder => $value) {
                    if (preg_match($value[self::REGEX], $matches[0])) {
                        $this->params[$placeholder] = $matches[0];
                        $this->next = &$value[self::NEXT];
                        break;
                    }
                }
            }
            $this->pointer = &$this->next;
        });
        if (is_callable($this->pointer[self::MAKE])) {
            call_user_func_array($this->pointer[self::MAKE], $this->params);
        } else {
            echo '404';
        }
    }

    public function getData() {
        return $this->data;
    }
}
