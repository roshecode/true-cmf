<?php

namespace Truth\Support\Services\Routing\TrueRouter;

class Router
{
    // ([^/]|\\\/)+[^\\](?=\/)|.$
    // '~:?[^/]+(?!/)?~i'
    const REGEXP_ROUTES_SPLITTING = '/(?:(?>\\\)\/|[^\/\s])+/i';

    const KEY_STEADY = 0;
    const KEY_VARIED = 1;
    const KEY_REGEXP = 2;
    const KEY_LAUNCH = 3;

    protected $host;
    protected $path;
    protected $query;

    protected $switcher;
    protected $pointer;

    protected static $filters = [
        's0' => '^[\w]*$',
        's' => '^[\w]+$', // true/:user|c+s/:name:\w{3}[/profile]
        'c' => '^[a-z]+$',
        'i' => '^\d+$',
        'cs' => '^[a-z]+[\w]*$',
    ];

    public static function getFilter($name) {
        return self::$filters[$name];
    }

    public function __construct($host = null, $data = [])
    {
        $this->host = $host;
        $this->switcher = $data;
    }

    public function split($uri, $callback) {
        preg_replace_callback(self::REGEXP_ROUTES_SPLITTING, function ($matches) use ($callback) {
            $callback($matches[0]);
        }, $uri);
    }

    public function match($methods, $uri, $handler) {
        $this->pointer = &$this->switcher;
        $this->split($this->host . $uri, function($node) {
            if ($node[0] === ':') {
                $parts_regexp = explode(':', $node, 3);
                $node = &$parts_regexp[1];
                if (isset($parts_regexp[2])) {
                    $this->pointer[self::KEY_REGEXP][] = &$parts_regexp[2];
                } else {
                    $parts_filter = explode('|', $node, 2);
                    if (isset($parts_filter[1])) {
                        $node = &$parts_filter[0];
                        $this->pointer[self::KEY_REGEXP][] = preg_replace_callback('/\+|\w+/i', function($matches) {
                            return $matches[0] == '+' ? '' : trim(Router::getFilter($matches[0]), '^$');
                        }, $parts_filter[1]);
                    }
                }
                $this->pointer = &$this->pointer[self::KEY_VARIED][$node];
            } else {
                $this->pointer = &$this->pointer[self::KEY_STEADY][$node];
            }

//            $node[0] == ':' ? (
//                ($parts_regexp = explode(':', $node, 3)) &&
//                ($node = &$parts_regexp[1]) &&
//                isset($parts_regexp[2]) ? (
//                    $this->pointer[self::KEY_REGEXP][] = &$parts_regexp[2]
//                ) : (
//                    ($parts_filter = explode('|', $node, 2)) &&
//                    !isset($parts_filter[1]) ?: (
//                        ($node = &$parts_filter[0]) &&
//                            ($this->pointer[self::KEY_REGEXP][] = preg_replace_callback('/\+|\w+/i', function($matches) {
//                            return $matches[0] == '+' ? '' : trim(Router::getFilter($matches[0]), '^$');
//                        }, $parts_filter[1])))
//                ) &&
//                ($this->pointer = &$this->pointer[self::KEY_VARIED][$node])
//            ) : (
//                $this->pointer = &$this->pointer[self::KEY_STEADY][$node]
//            );
        });
        $this->pointer[self::KEY_LAUNCH] = $handler;
    }

    public function run() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
        if ($parse_uri) {
            $this->path = $parse_uri['path'];
            $this->query = isset($parse_uri['query']) ? $parse_uri['query'] : null;
//            $this->make($_SERVER['HTTP_HOST'] . $this->path);
        } else throw new \Exception('404');
    }

    public function __destruct()
    {
//        $_SERVER['HTTP_HOST'] = 'true';
//        $_SERVER['REQUEST_URI'] = '/';
        $this->run();
    }
}