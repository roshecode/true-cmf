<?php

namespace Truth\Support\Services\Routing\RegexRouter;

class RegexRouter
{
    const ROUTES_GROUP_COUNT = 34;
    const ROUTES_SPLIT_REGEX = '/(?:(?>\\\)\/|[^\/\s])+/i';

    protected $routes = [];
    protected $count = -1;
    protected $iteration = -1;
    protected $tail;

    public function __construct()
    {
        $this->tail = ':' . str_repeat('-', self::ROUTES_GROUP_COUNT - 1);
    }

    public function split($uri, $callback) {
        return preg_replace_callback(self::ROUTES_SPLIT_REGEX, $callback, $uri);
    }

    protected function wrap($regex) {
        return '(?:' . $regex . '):(-{' . $this->iteration . '})';
    }

    public function match($methods, $route, $handler) {
        $this->iteration = ++$this->count % self::ROUTES_GROUP_COUNT;
        $regex = $this->wrap($this->split($route, function ($matches) {
            $node = &$matches[0];
            if ($node[0] == ':') {
                $parts_regexp = explode(':', $node, 3);
                return '(' . (isset($parts_regexp[2]) ? $parts_regexp[2] : '[^/]+') . ')';
            }
            return $node;
        }));
        $route = &$this->routes[intval($this->count / self::ROUTES_GROUP_COUNT)];
        $this->iteration ? $route[0] .= '|' . $regex :
            $route = \SplFixedArray::fromArray([$regex, new \SplFixedArray(self::ROUTES_GROUP_COUNT)]);
        $route[1][$this->iteration] = $handler;
    }

    public function make($uri) {
        $uri .= $this->tail;
        $matches = [];
        foreach ($this->routes as &$group) {
            if (preg_match('~^(?|' . $group[0] . ')~i', $uri, $matches)) {
                unset($matches[0]);
                return [$group[1][strlen(array_pop($matches))], &$matches];
            }
        }
        return 404;
    }
}