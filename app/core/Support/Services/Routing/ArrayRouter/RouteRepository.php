<?php

namespace T\Support\Services\Routing\ArrayRouter;

class RouteRepository
{
    const POST      = 'POST';
    const GET       = 'GET';
    const DELETE    = 'DELETE';
    const PUT       = 'PUT';
    const PATCH     = 'PATCH';
    const OPTIONS   = 'OPTIONS';

    const CODE_403  = 403;
    const CODE_404  = 404;

    const CONFIG_SEPARATOR         = 'separator';
    const CONFIG_PLACEHOLDER       = 'placeholder';
    const CONFIG_REGEX_WRAPPER     = 'regex_wrapper';

    const KEY_REGEX         = 0;
    const KEY_NEXT          = 1;
    const KEY_PLACEHOLDERS  = ':';
    const KEY_MAKE          = '::';

    const FILTER_LETTERS    = 'letters';
    const FILTER_INT        = 'int';
    const FILTER_UINT       = 'uint';

    protected $data;
    protected $pointer;
    protected $params;
    protected $config;
    protected $filters = [
        self::FILTER_LETTERS    => '^[a-zA-Z]+$',
        self::FILTER_INT        => '^-?\d+$',
        self::FILTER_UINT       => '^\d+$',
    ];

    public function __construct(array $data = [], $config = [
        self::CONFIG_SEPARATOR => '\/', self::CONFIG_PLACEHOLDER => ':', self::CONFIG_REGEX_WRAPPER => '/'])
    {
        $this->data = $data;
        $this->config = $config;
    }

    protected function pass($query, $callback) {
        $this->pointer = &$this->data;
//        preg_replace_callback('~' . $this->placeholder . '?[\w-]+(?!/)?~i', $callback, $query);
        preg_replace_callback(
            $this->config[self::CONFIG_REGEX_WRAPPER]       . $this->config[self::CONFIG_PLACEHOLDER] . '?[^' .
            $this->config[self::CONFIG_SEPARATOR] . ']+(?!' . $this->config[self::CONFIG_SEPARATOR  ] . ')?'  .
            $this->config[self::CONFIG_REGEX_WRAPPER] . 'i', $callback, $query);
    }

    public function match($methods, $query, $callback) {
        $this->pass($query, function($matches) {
            if ($matches[0][0] === ':') {
                $parts_preg     = explode(':', $matches[0], 3);
                $parts_filter   = explode('|', $parts_preg[1], 2);
                if (isset($parts_filter[1])) {
                    $parts_preg[1] = $parts_filter[0];
                    $regex = $this->filters[$parts_filter[1]];
                } elseif (isset($parts_preg[2])) {
                    $regex = $parts_preg[2];
                } else {
                    $regex = '';
                }
                $placeholder = &$this->pointer[':'][$parts_preg[1]];
                $placeholder = [
                    self::KEY_REGEX => $this->config[self::CONFIG_REGEX_WRAPPER] .
                             $regex .  $this->config[self::CONFIG_REGEX_WRAPPER]
                ];
                $this->pointer = &$placeholder[self::KEY_NEXT];
            } else {
                $this->pointer = &$this->pointer[$matches[0]];
            }
        });
        $this->pointer[self::KEY_MAKE] = $callback;
    }

    public function make($query) {
        $this->params = []; // reset params
        $this->pass($query, function($matches) {
            if (isset($this->pointer[$matches[0]])) { // check static routes
                $this->pointer = &$this->pointer[$matches[0]];
            } elseif (isset($this->pointer[':'])) { // check placeholders
                $placeholders = &$this->pointer[':'];
                $last = 0;
                $length = count($placeholders);
                foreach ($placeholders as $placeholder => $value) {
                    if (preg_match($value[self::KEY_REGEX], $matches[0])) {
                        $this->params[$placeholder] = $matches[0];
                        $this->pointer = &$value[self::KEY_NEXT];
                        break;
                    }
                    ++$last;
                }
                if ($last == $length) throw new \Exception('404', self::CODE_404); // if nothing matched
            } else throw new \Exception('404', self::CODE_404); // if nothing matched
        });
        if (isset($this->pointer[self::KEY_MAKE]) && is_callable($this->pointer[self::KEY_MAKE])) {
            call_user_func_array($this->pointer[self::KEY_MAKE], $this->params);
        } else throw new \Exception('404', self::CODE_404);
    }

    public function getData() {
        return $this->data;
    }
}
