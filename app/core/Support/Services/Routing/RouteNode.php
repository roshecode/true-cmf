<?php

namespace Truth\Support\Services\Routing;

class RouteNodeDynamic
{
    protected $regex;
    protected $next;

    public function __construct($regex) {
        $this->regex = $regex;
    }

    public function &next() {
        return $this->next;
    }
}

class RouteNode
{
    const KEY_REGEX         = 0;
    const KEY_NEXT          = 1;
    const KEY_PLACEHOLDERS  = ':';
    const KEY_MAKE          = '::';

    const FILTER_LETTERS    = 'letters';
    const FILTER_INT        = 'int';
    const FILTER_UINT       = 'uint';

    protected $params;

    protected $staticNodes;
    protected $dynamicNodes;

    protected static $filters = [
        self::FILTER_LETTERS    => '^[a-zA-Z]+$',
        self::FILTER_INT        => '^-?\d+$',
        self::FILTER_UINT       => '^\d+$',
    ];

    public function __construct($name, $regexWrapper = '/')
    {
        if ($name[0] === ':') {
            $parts_preg     = explode(':', $name, 3);
            $parts_filter   = explode('|', $parts_preg[1], 2);
            if (isset($parts_filter[1])) {
                $parts_preg[1] = $parts_filter[0];
                $regex = self::$filters[$parts_filter[1]];
            } elseif (isset($parts_preg[2])) {
                $regex = $parts_preg[2];
            } else {
                $regex = '';
            }
            $placeholder = &$this->dynamicNodes[$parts_preg[1]];
            $placeholder = new RouteNodeDynamic($regexWrapper . $regex . $regexWrapper);
            $this->next = &$placeholder->next();
        } else {
            $this->next = &$this->staticNodes[$name];
        }
    }

    public function &match($value) {
        if (isset($this->staticNodes[$value])) {
            return $this->staticNodes[$value];
        } elseif (isset($this->dynamicNodes)) {
            $last = 0;
            $length = count($this->dynamicNodes);
            foreach ($this->dynamicNodes as $nodeName => $regex) {
                if (preg_match($regex, $value)) {
                    $this->params[$nodeName] = $value;
                }
            }
        }
    }

    public function addChild($name) {
        $this->children[$name] = new Route;
    }
}
